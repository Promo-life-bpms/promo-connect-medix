<?php

namespace App\Http\Controllers;

use App\Models\CurrentQuoteDetails;
use App\Models\Quote;
use App\Models\QuoteTechniques;
use App\Models\SpecialRequest;
use App\Models\User;
use App\Notifications\SendEmailCotizationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipStream\Option\Archive;

class CotizacionController extends Controller
{
    public function generarPDF(){
    
        $date =  Carbon::now()->format("d/m/Y");

        $cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
        $totalQuote = 0;

        foreach ($cotizacionActual as $productToSum) {
            if ($productToSum->quote_by_scales) {
                try {
                    $totalQuote = $totalQuote + floatval(json_decode($productToSum->scales_info)[0]->total_price);
                } catch (Exception $e) {
                    $totalQuote = $totalQuote + 0;
                }
            } else {
                $totalQuote = $totalQuote + $productToSum->precio_total;
            }
        }

        $total = $totalQuote;
        if (auth()->user()->currentQuote->type == 'Fijo') {
            $discountMount = auth()->user()->currentQuote->value;
        } else {
            $discountMount = round((($totalQuote / 100) * auth()->user()->currentQuote->value), 2);
        }
        $discount = $discountMount;
        
        $cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
        
     /*    $pdf = \PDF::loadView('pages.pdf.cotizacionBH', ['date' =>$date, 'cotizacionActual'=>$cotizacionActual ]);
        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream("QS-1". '.pdf');  */

        $pdf = \PDF::loadView('pages.pdf.cotizacionBH', ['date' => $date, 'cotizacionActual' => $cotizacionActual]);
        $pdf->setPaper('Letter', 'portrait');
        $filename = "QS-1.pdf";
        $pdf->save(public_path($filename));

        return response()->download(public_path($filename))->deleteFileAfterSend(true);
        
    }

    public function downloadPDF(Request $request)
    {

        $date =  Carbon::now()->format("d/m/Y");

        $user = auth()->user();
        $quotes = Quote::where('user_id', $user->id)->where('id',$request->id)->get();
        
        $recipients = [
            'daniel@trademarket.com.mx',
            'ugamboa@medix.com.mx',
            'jsantos@medix.com.mx',
        ];

        $pdf = \PDF::loadView('pages.pdf.quoteBH', ['date' => $date, 'quotes' => $quotes]);
        $pdf->setPaper('Letter', 'portrait');
        $filename = "Cotizacion.pdf";
        $pdf->save(public_path($filename));
   
        return response()->download(public_path($filename))->deleteFileAfterSend(true);
        
    }

    public function special() {
    
        $user = auth()->user();
        if ($user->hasRole(['buyers-manager', 'seller' ])) {
            $special_requests = SpecialRequest::latest()->paginate(10);
        }else{
            $special_requests = SpecialRequest::where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('pages.seller.special', compact('special_requests'));
        
    }

    public function specialStorage(Request $request) {

        if ($request->hasFile('image_reference')) {
            $filenameWithExt = $request->file('image_reference')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image_reference')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $pathImage = $request->file('image_reference')->move('storage/special/img/', $fileNameToStore);
        } else {
            $pathImage = '';
        }

        if ($request->hasFile('file')) {
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $pathFile = $request->file('file')->move('storage/special/files/', $fileNameToStore);
        } else {
            $pathFile = '';
        }

        $create_special_request = new SpecialRequest();
        $create_special_request->description = $request->description;
        $create_special_request->file = $pathFile ;
        $create_special_request->image_reference = $pathImage ;
        $create_special_request->status = 1;
        $create_special_request->user_id = auth()->user()->id;
        $create_special_request->save();

        return back()->with('mensaje', 'Pedido especial creado con éxito');

    }

    public function specialUpdate(Request $request) {

        $find_special_request = SpecialRequest::where('id', $request->id)->get()->first();
        $pathImage = '';
        $pathFile = '';

        if ($request->hasFile('image_reference')) {
            $filenameWithExt = $request->file('image_reference')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image_reference')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;

            Storage::delete($find_special_request->image_reference);

            $pathImage = $request->file('image_reference')->move('storage/special/img/', $fileNameToStore);

        }else{
            $pathImage = $find_special_request->image_reference == null ? '': $find_special_request->image_reference;
        }
       
        if ($request->hasFile('file')) {
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;

            Storage::delete($find_special_request->file);

            $pathFile = $request->file('file')->move('storage/special/files/', $fileNameToStore);
        }else{
            $pathFile = $find_special_request->file == null ? '': $find_special_request->file;
        }

        DB::table('special_request')->where('id', $request->id)->update([
            'description' => $request->description,
            'file' => $pathFile,
            'image_reference' => $pathImage,
        ]);

        return back()->with('mensaje', 'Pedido especial creado con éxito');

    }
}
