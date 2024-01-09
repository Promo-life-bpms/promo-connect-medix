<?php

namespace App\Http\Controllers;

use App\Mail\SendDataErrorCreateQuote;
use App\Models\Banner;
use App\Models\Catalogo\Category;
use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Client;
use App\Models\CommentsSupport;
use App\Models\Muestra;
use App\Models\Quote;
use App\Models\QuoteDiscount;
use App\Models\QuoteInformation;
use App\Models\QuoteProducts;
use App\Models\QuoteTechniques;
use App\Models\QuoteUpdate;
use App\Models\Shopping;
use App\Models\ShoppingDiscount;
use App\Models\ShoppingInformation;
use App\Models\ShoppingProduct;
use App\Models\ShoppingTechnique;
use App\Models\ShoppingUpdate;
use App\Models\User;
use App\Notifications\PurchaseMadeNotification;
use App\Notifications\SendEmailCotizationNotification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\WithPagination;
use SimpleXMLElement;

class CotizadorController extends Controller
{
    // use WithPagination;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $valores = [];
        for ($x = 0; $x < 100; $x++) {
            $num_aleatorio = rand(1, 14000);
            array_push($valores, $num_aleatorio);
        }

        $valores2 = [];
        for ($x = 0; $x < 100; $x++) {
            $num_aleatorio = rand(1, 14000);
            array_push($valores2, $num_aleatorio);
        }

        // Obtener los banner visibles, del primero al último
        $banners = Banner::where('visible', true)->orderBy('created_at', 'desc')->get();

        $getLatestProducts = Product::whereIn('id', $valores2)->get();
        $latestCategorias = Category::withCount('productCategories')
            ->orderBy('product_categories_count', 'DESC')
            ->where('family', 'not like', '%textil%')
            ->limit(6)
            ->get();
        $getmoreProducts = Product::whereIn('id', $valores)->get();

        $moreProducts = [];
        $latestProducts = [];
        $validColorIds = [2, 3, 12, 13, 41, 44, 48, 53, 54, 58, 60, 62, 63, 68, 69, 70, 78, 81];

        foreach ($getmoreProducts as  $getmoreProduct) {
            if (isset($getmoreProduct->color->id)) {
                if (in_array($getmoreProduct->color->id, $validColorIds) && count($moreProducts) < 6) {
                    array_push($moreProducts, $getmoreProduct);
                }
            }
        }

        foreach ($getLatestProducts as  $getmoreProduct2) {
            if (isset($getmoreProduct2->color->id)) {
                if (in_array($getmoreProduct2->color->id, $validColorIds) && count($latestProducts) < 6) {
                    array_push($latestProducts, $getmoreProduct2);
                }
            }
        }

        return view('home', compact('latestProducts', 'latestCategorias', 'moreProducts', 'banners'));
    }

    public function categoryfilter($category)
    {
        session()->put('category', $category);
        return redirect('/catalogo');
    }

    public function catalogo()
    {
        return view('pages.catalogo.catalogo');
    }

    public function verProducto(Product $product)
    {
        $utilidad = (float) config('settings.utility');
        $msg = '';
        // Consultar las existencias de los productos en caso de ser de Doble Vela.
        if ($product->provider_id == 5) {
            $cliente = new \nusoap_client('http://srv-datos.dyndns.info/doblevela/service.asmx?wsdl', 'wsdl');
            $error = $cliente->getError();
            if ($error) {
                echo 'Error' . $error;
            }
            //agregamos los parametros, en este caso solo es la llave de acceso
            $parametros = array('Key' => 't5jRODOUUIoytCPPk2Nd6Q==', 'codigo' => $product->sku_parent);
            //hacemos el llamado del metodo
            $resultado = $cliente->call('GetExistencia', $parametros);
            $msg = '';
            if (array_key_exists('GetExistenciaResult', $resultado)) {
                $informacionExistencias = json_decode(utf8_encode($resultado['GetExistenciaResult']))->Resultado;
                if (count($informacionExistencias) > 1) {
                    foreach ($informacionExistencias as $productExistencia) {
                        if ($product->sku == $productExistencia->CLAVE) {
                            $product->stock = $productExistencia->EXISTENCIAS;
                            $product->save();
                            break;
                        }
                        $msg = "Este producto no se encuentra en el catalogo que esta enviado DV via Servicio WEB";
                    }
                } else {
                    $msg = "Este producto no se encuentra en el catalogo que esta enviado DV via Servicio WEB";
                }
            } else {
                $msg = "No se obtuvo informacion acerca del Stock de este producto. Es posible que los datos sean incorrectos";
            }
        }
        return view('pages.catalogo.product', compact('product', 'utilidad', "msg"));
    }

    public function cotizacion()
    {
        $cotizacionActual = [];

        $total = 0;
        if (auth()->user()->currentQuote) {
            $cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
            $total = $cotizacionActual->sum('precio_total');
        }
        return view('pages.catalogo.cotizacion-actual', compact('cotizacionActual', 'total'));
    }

    public function procesoMuestra($id)
    {
        return view('pages.catalogo.proceso-muestra', compact('id'));
    }

    public function infoperfil($id)
    {
        $datainforusers = User::where("id", $id)->select("users.*")->get();
        $db = config('database.connections.mysql_catalogo.database');
        $userproducts = Muestra::join('users', 'users.id', 'muestras.user_id')
            ->join($db . ".products",  'muestras.product_id', $db . ".products.id")
            ->select('users.name as user_name', 'products.name as product_name as product_name', 'muestras.updated_at', 'muestras.address',  'muestras.current_quote_id', 'muestras.id as id_muestra')
            ->where('users.id', $id)
            ->get();

        $usercompras = Quote::join('users', 'users.id', 'quotes.user_id')
            ->join('quote_updates', 'quote_updates.quote_id', 'quotes.id')
            ->join('quote_products', 'quote_products.id', 'quote_updates.id')
            ->where('users.id', $id)
            ->get();

        $longitudcompras = count($usercompras);
        $longitudmuestras = count($userproducts);
        return view('pages.catalogo.info-user', compact('id'), ['infouser' => $datainforusers, 'muestras' => $userproducts, 'longitudmuestras' => $longitudmuestras, 'compras' => $usercompras, 'longitudcompras' => $longitudcompras]);
    }

    public function administrador()
    {
        return view('pages.catalogo.administrador');
    }

    public function administradorcompras()
    {
        return view('pages.catalogo.administradorcompras');
    }

    public function administradorpedidos()
    {
        return view('pages.catalogo.administradorpedidos');
    }


    public function cotizaciones()
    {
        return view('pages.catalogo.cotizaciones');
    }

    public function compras()
    {
        return view('pages.catalogo.compras');
    }

    public function muestras()
    {
        return view('pages.catalogo.muestras');
    }

    public function settings()
    {
        return view('pages.catalogo.settings');
    }

    public function verCotizacion(Quote $quote)
    {
        return view('pages.catalogo.ver-cotizacion', compact('quote'));
    }

    public function finalizar()
    {
        return view('pages.catalogo.finalizar');
    }
    public function previsualizar(Quote $quote)
    {
        $pdf = \PDF::loadView('pages.pdf.bh', ['quote' => $quote, 'nombreComercial' => ""]);
        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream("QS-" . $quote->id . " " . $quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $quote->updated_at->format('d/m/Y') . '.pdf');
    }


    public function all()
    {
        return view('pages.catalogo.cotizaciones-all');
    }
    public function dashboard()
    {
        return view('pages.catalogo.dashboard');
    }

    public function exportUsuarios()
    {

        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("Aquí va el creador, como cadena")
            ->setLastModifiedBy('Parzibyte') // última vez modificado por
            ->setTitle('Mi primer documento creado con PhpSpreadSheet')
            ->setSubject('El asunto')
            ->setDescription('Este documento fue generado para parzibyte.me')
            ->setKeywords('etiquetas o palabras clave separadas por espacios')
            ->setCategory('La categoría');

        $nombreDelDocumento = "Reporte de Usuarios con corte al " . now()->format('d-m-Y') . ".xlsx";

        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("Usuarios");
        $users = User::where('visible', 1)->get();
        $i = 2;
        $hoja->setCellValueByColumnAndRow(1, 1,  'Nombre');
        $hoja->setCellValueByColumnAndRow(2, 1,  'Apellido');
        $hoja->setCellValueByColumnAndRow(3, 1,  'Correo');
        $hoja->setCellValueByColumnAndRow(4, 1,  'Ultimo Inicio de Sesion');

        foreach ($users as $user) {
            $hoja->setCellValueByColumnAndRow(1, $i,  $user->name);
            $hoja->setCellValueByColumnAndRow(2, $i,  $user->lastname);
            $hoja->setCellValueByColumnAndRow(3, $i,  $user->email);
            $hoja->setCellValueByColumnAndRow(4, $i,  $user->last_login != null ? $user->last_login : "No hay Registro");
            $i++;
        }

        /**
         * Los siguientes encabezados son necesarios para que
         * el navegador entienda que no le estamos mandando
         * simple HTML
         * Por cierto: no hagas ningún echo ni cosas de esas; es decir, no imprimas nada
         */

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function enviarCompra(Request $request)
    {
        $request->validate(['direccionSeleccionada' => 'required']);

        if (Auth::user()->hasRole('seller')) {
            return 'No tienes permisos para realizar esta acción';
        }

        $currentSale = auth()->user()->currentQuote;

        $quote = auth()->user()->quotes()->create([
            'iva_by_item' => 1,
            'address_id' => $request->direccionSeleccionada,
            'show_total' => 1,
            'logo' => '',
            'status' => 0
        ]);

        // Guardar la Info de la cotizacion
        $quoteInfo = QuoteInformation::create([
            'name' => "Cliente",
            'company' => "Company",
            'email' => "email",
            'landline' => 00001,
            'cell_phone' => 00001,
            'oportunity' => "Oportunidad",
            'rank' => 1,
            'department' => "Departamento",
            'information' => "Info",
            'tax_fee' => 0,
            'shelf_life' =>  10,
        ]);

        // Guardar descuento
        $type = 'Fijo';
        $value = 0;
        $discount = false;
        if (auth()->user()->currentQuote->discount) {
            $discount = true;
            $type = auth()->user()->currentQuote->type;
            $value = auth()->user()->currentQuote->value;
        }

        $dataDiscount = [
            'discount' => $discount,
            'type' => $type,
            'value' => $value,
        ];

        $quoteDiscount = QuoteDiscount::create($dataDiscount);

        // Guardar la actualizacion
        $quoteUpdate = $quote->quotesUpdate()->create([
            'quote_information_id' => $quoteInfo->id,
            'quote_discount_id' => $quoteDiscount->id,
            'type' => "created"
        ]);

        // Ligar Productos al update

        // Guardar los productos de la cotizacion
        foreach (auth()->user()->currentQuote->currentQuoteDetails as $item) {
            $product = Product::find($item->product_id);
            // Agregar la URL de la Imagen
            $product->image = $item->images_selected == null ? ($product->firstImage == null ? '' : $product->firstImage->image_url) : $item->images_selected;
            unset($product->firstImage);
            $product->provider;

            $dataProduct = [
                'product' => json_encode($product->toArray()),
                'technique' =>  json_encode(["price_technique" => $item->price_technique]),
                'new_description' => "jid",
                'color_logos' => $item->color_logos,
                'costo_indirecto' => 0,
                'utilidad' => 0,
                'dias_entrega' => $item->dias_entrega,
            ];
            $price_tecnica = $item->price_technique;
            $dataProduct['prices_techniques'] = $price_tecnica;
            $dataProduct['cantidad'] = $item->cantidad;
            $dataProduct['precio_unitario'] = $item->precio_unitario;
            $dataProduct['precio_total'] = $item->precio_total;
            $dataProduct['quote_by_scales'] = false;
            $dataProduct['scales_info'] = null;

            $quoteUpdate->quoteProducts()->create($dataProduct);
        }

        // Buscar usuarios con el rol de vendedor y gerente de compras
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['buyers-manager', 'seller']);
        })->get();

        foreach ($users as $user) {
            // Enviar notificacion a los usuarios con el rol de vendedor y gerente de compras
            $user->notify(new PurchaseMadeNotification(auth()->user()->name));
        }




        // Crear un nuevo objeto SimpleXMLElement
        $xml = new SimpleXMLElement('<!DOCTYPE cXML SYSTEM "http://xml.cxml.org/schemas/cXML/1.2.014/cXML.dtd"><cXML></cXML>');

        // Agregar atributos al elemento cXML
        $xml->addAttribute('payloadID', '1686259207.6514087@stg791app53.int.coupahost.com');
        $xml->addAttribute('xml:lang', 'en-US');
        $xml->addAttribute('timestamp', now());
        $xml->addAttribute('version', '1.2.0.14');

        // Agregar el elemento Header
        $header = $xml->addChild('Header');

        // Agregar el elemento From dentro de Header
        $from = $header->addChild('From');
        $fromCredential = $from->addChild('Credential');
        $fromCredential->addAttribute('domain', 'NetworkID');
        $fromCredential->addChild('Identity', auth()->user()->email);

        // Agregar el elemento To dentro de Header
        $to = $header->addChild('To');
        $toCredential = $to->addChild('Credential');
        $toCredential->addAttribute('domain', 'NetworkId');
        $toCredential->addChild('Identity', auth()->user()->email);

        // Agregar el elemento Sender dentro de Header
        $sender = $header->addChild('Sender');
        $senderCredential = $sender->addChild('Credential');
        $senderCredential->addAttribute('domain', 'NetworkID');
        $senderCredential->addChild('Identity', auth()->user()->email);
        $senderCredential->addChild('SharedSecret', auth()->user()->punchoutSession->sharesecret);
        $sender->addChild('UserAgent', 'bhtrade');

        // Agregar el elemento Message
        $message = $xml->addChild('Message');
        // Agregar el elemento PunchOutOrderMessage dentro de Message
        $punchOutOrderMessage = $message->addChild('PunchOutOrderMessage');
        $punchOutOrderMessage->addChild('BuyerCookie', auth()->user()->punchoutSession->cookie);
        // Agregar el elemento PunchOutOrderMessageHeader dentro de PunchOutOrderMessage
        $punchOutOrderMessageHeader = $punchOutOrderMessage->addChild('PunchOutOrderMessageHeader');
        $punchOutOrderMessageHeader->addAttribute('operationAllowed', 'edit');

        // Agregar el elemento Total dentro de PunchOutOrderMessageHeader
        $total = $punchOutOrderMessageHeader->addChild('Total');
        $money = $total->addChild('Money', $currentSale->currentQuoteDetails->sum('precio_total'));
        $money->addAttribute('currency', 'MXN');
        foreach ($currentSale->currentQuoteDetails as $currentQuote) {
            // Agregar el elemento ItemIn dentro de PunchOutOrderMessage
            $itemIn = $punchOutOrderMessage->addChild('ItemIn');
            $itemIn->addAttribute('quantity', $currentQuote->cantidad);

            // Agregar el elemento ItemID dentro de ItemIn
            $itemID = $itemIn->addChild('ItemID');
            $itemID->addChild('SupplierPartID', $currentQuote->product->internal_sku);

            // Agregar el elemento ItemDetail dentro de ItemIn
            $itemDetail = $itemIn->addChild('ItemDetail');
            $unitPrice = $itemDetail->addChild('UnitPrice');
            $money = $unitPrice->addChild('Money', $currentQuote->precio_unitario);
            $money->addAttribute('currency', 'MXN');
            $itemDetail->addChild('Description', $currentQuote->product->description)->addAttribute('xml:lang', 'en-US');
            $itemDetail->addChild('UnitOfMeasure', 'PZ');
            $itemDetail->addChild('Classification', '80141605')->addAttribute('domain', 'UNSPSC');
        }

        $xmlString = $xml->asXML();
        $cXMLString = str_replace("lang=", "xml:lang=", $xmlString);


        $url = 'https://grupoherdez-test.coupahost.com/punchout/checkout?id=11';

        // Eliminar los datos del carrito
        auth()->user()->currentQuote->currentQuoteDetails()->delete();
        auth()->user()->currentQuote()->delete();

        return view('pages.catalogo.submitCoupa', compact('url', 'cXMLString'));
    }

    public function misCotizaciones()
    {
     
        if(auth()->user()->hasRole( "buyers-manager")){
            $quotes = Quote::simplePaginate(10);
        }else{
            $quotes =  auth()->user()->quotes()->simplePaginate(10);
        }
    
        return view('pages.catalogo.misCotizaciones', compact('quotes'));
    }

    public function comprasStatus(Request $request) {

        DB::table('shoppings')->where('id', $request->shopping_id)->update([
            'status' => $request->status,
        ]);

        return redirect()->action([CotizadorController::class, 'compras']);
    }

    public function comprasRealizarCompra(Request $request) {
 
        $quote = Quote::where('id', $request->id )->get()->first();
        $quote_products = QuoteProducts::where('id', $request->id )->get()->first();
        $quote_techniques = QuoteTechniques::where('id', $request->id )->get()->first();
        $quote_updates = QuoteUpdate::where('id', $request->id )->get()->first();
/*         $quote_update_product = QuoteProducts::where('id', $request->id )->get()->first();
 */
        if($quote){
            $createQuote = new Shopping(); 
            $createQuote->user_id = $quote->user_id;
            $createQuote->address_id = $quote->address_id;
            $createQuote->iva_by_item = $quote->iva_by_item;
            $createQuote->show_total = $quote->show_total;
            $createQuote->logo = $quote->logo;
            $createQuote->status = 0;
            $createQuote->save();
        } 

        $createQuoteDiscount = new ShoppingDiscount();
        $createQuoteDiscount->discount = 0;
        $createQuoteDiscount->type = 'Fijo';
        $createQuoteDiscount->value = 0.00;
        $createQuoteDiscount->save();

        $createQuoteInformation = new ShoppingInformation();
        $createQuoteInformation->name = 'Cliente';
        $createQuoteInformation->email = 'email';
        $createQuoteInformation->landline = '1';
        $createQuoteInformation->cell_phone = '1';
        $createQuoteInformation->oportunity = 'Oportunidad';
        $createQuoteInformation->rank = '1';
        $createQuoteInformation->department = 'Departamento';
        $createQuoteInformation->information = 'Info';
        $createQuoteInformation->tax_fee = 0;
        $createQuoteInformation->shelf_life = 10;
        $createQuoteInformation->save();

        $createQuoteProduct = new ShoppingProduct();
        $createQuoteProduct->product = $quote_products->product;
        $createQuoteProduct->technique = $quote_products->technique;
        $createQuoteProduct->prices_techniques = $quote_products->prices_techniques;
        $createQuoteProduct->color_logos = $quote_products->color_logos;
        $createQuoteProduct->costo_indirecto = $quote_products->costo_indirecto;
        $createQuoteProduct->utilidad = $quote_products->utilidad;
        $createQuoteProduct->dias_entrega = $quote_products->dias_entrega;
        $createQuoteProduct->cantidad = $quote_products->cantidad;
        $createQuoteProduct->precio_unitario = $quote_products->precio_unitario;
        $createQuoteProduct->precio_total = $quote_products->precio_total;
        $createQuoteProduct->shopping_by_scales = 0;
        $createQuoteProduct->scales_info = $quote_products->scales_info;
        $createQuoteProduct->shopping_id = $createQuote->id;
        $createQuoteProduct->save();

        /* if($quote_updates){
            $createQuoteUpdate = new ShoppingUpdate();
            $createQuoteUpdate->shopping_id = $createQuote->id;
            $createQuoteUpdate->shopping_information_id = $quote_updates->shopping_information_id;
            $createQuoteUpdate->shopping_discount_id = $quote_updates->shopping_discount_id;
            $createQuoteUpdate->type = 'created';
            $createQuoteUpdate->save();
        } */

        if($quote_techniques){
            $createQuoteTechniques = new ShoppingTechnique();
            $createQuoteTechniques->quotes_id = $createQuote->id;
            $createQuoteTechniques->material =  $quote_techniques->material->material;
            $createQuoteTechniques->technique = $quote_techniques->technique->technique;
            $createQuoteTechniques->size = $quote_techniques->size->size;
            $createQuoteTechniques->save();
        }

        $recipients = [
            'daniel@trademarket.com.mx',
            'ugamboa@medix.com.mx',
            'jsantos@medix.com.mx',
        ];

        $date = Carbon::now()->format("d/m/Y");

    
        Notification::route('mail', [
            'daniel@trademarket.com.mx',
            'ugamboa@medix.com.mx',
            'jsantos@medix.com.mx',
        ])->notify(new SendEmailCotizationNotification($date, $quote));

        $user = auth()->user(); 
        $userEmail = $user->email; 

        Notification::route('mail', $userEmail)
            ->notify(new SendEmailCotizationNotification($date, $quote));
            
        return redirect()->back()->with('message', 'Este es tu mensaje de sesión.');

    }
}
