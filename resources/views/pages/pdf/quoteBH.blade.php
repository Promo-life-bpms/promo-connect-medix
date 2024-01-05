<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion</title>
    <link rel="stylesheet" href="quotesheet/bh/stylebh.css">
    <link rel="stylesheet" href="quotesheet/pz/style.css">

</head>

<body>
  {{--   <style>
      
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        } 

        th{
            background-color: black;
            color: white;
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            border-color: #006EAD;
        }
        
    </style> --}}

    <header>
        <img src="quotesheet/pz/fondo-azul-superior.png" alt="" srcset="" class="fondo-head">
        <table class="head content">
            <tr>
                <td rowspan="3"><img src="quotesheet/pz/logo.jpg" class="logo"></td>
                <td colspan="6" class="company">PROMO ZALE S.A. DE C.V.</td>
            </tr>
            <tr>
                <td style="text-align: left;" colspan="6" class="company-quote">Cotizacion</td>
            </tr>
        </table>
    </header>

    {{-- <img src="quotesheet/bh/triangulos.png" alt="" srcset="" class="fondo-head"> --}}

    {{-- <header>
        
        <table style=" margin-bottom:16px;">
            <tr>
                <td style="width: 50%;">
                    <div style="display:flex; margin-top:10px; margin-left: 30px;">
                        <img src="img/bhtrade.png" style="width: 80px;" >
                    </div>
                    <p style="margin-left:30px; margin-top:10px; "><b>BH Trademarket S.A. de C.V.</b> </p>
                    <p style="margin-left:30px;">San Andrés Atoto 155A Naucalpan de Juárez, Méx. C.P. 53550 Tel. +52(55) 6269 0017</p>
                </td>
                
                <td style="text-align: left; width: 50%;">
                    <div style="margin-left:60px; display:flex;">
                        <img src="img/logo-color.png" style="width: 120px;" >
                    </div>

                    <p style="margin-left:60px; margin-top:28px;">Fecha: <b>{{$date}}</b></p>
                    <p style="margin-left:60px;"></p>
                </td>
            </tr>
        </table>
    </header> --}}

    {{-- <footer>
        <table class="footer content">
            <tr>
                <td colspan="3">
                    <center style="font-size: 20px; margin-bottom:5px;"><b>trademarket.com.mx</b></center>
                
                <center style="font-size:12px;">San Andrés Atoto 155A Naucalpan de
                    Juárez, Méx. C.P. 53550
                    Tel. +52(55) 6269 0017</center>
            </td>
            </tr>
            
        </table>
        <div style="text-align: right">
            <p class="content">Pagina <span class="pagenum"></span></p>
        </div>
    </footer> --}}

    <footer>
        <table
            style="magin-bottom: 0mm; position: absolute; bottom: 22mm; z-index: 20; width: 100%; margin-left: -10px;">
            <tr>
                <td>
                    <img src="quotesheet/pz/fondo-azul-inferior.png" />
                </td>
            </tr>
        </table>

        <table style="magin-bottom: 0mm; position: absolute; bottom: 60px; z-index:100;" class="content">
            <tr>
                <td>
                    <p style="font-size: 12px; margin-left:3px; color:#fff;">Pagina <span class="pagenum"></span> </p>
                    <br>
                    <p style="font-size: 12px; margin-left:3px; color:#fff; text-transform: uppercase">San Andr&#233;s
                        Atoto 155, San Est&#233;ban, Naucalpan, Edo. Méx. C.P. 53550 <br></p>
                </td>
            </tr>
        </table>
    </footer>
    
    <div style="margin-left:30px; margin-right:30px; margin-top:8px;">
        
        <div style="width: 100%; height:1px; background-color:black; margin-top:8px; "></div>
        
        @foreach($quotes as $quote)
            
            @php
                
                $quoteTechnique = optional(\App\Models\QuoteTechniques::where('quotes_id', $quote->id)->latest()->first());

                $product = optional(\App\Models\QuoteProducts::where('id', $quote->id)->latest()->first());

                $productData = json_decode($product->product);

                $productImage = \App\Models\Catalogo\Image::where('product_id', $productData->id)->get()->first();

                $productName = optional($productData)->name ?? 'Nombre no disponible';

                $productLogo = optional($productData)->logo;

                if($productLogo != '' || $productLogo !=null){
                    $filePath = public_path('/storage/logos/' . $productLogo);
                }else{
                    $logo = $productImage->image_url;

                    $filename = basename($logo);

                    $encodedFilename = rawurlencode($filename);

                    $encodedUrl = str_replace($filename, $encodedFilename, $logo);

                    $ch = curl_init($encodedUrl);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $imageData = curl_exec($ch);

                    curl_close($ch);

                    $image64 = base64_encode($imageData);
                }

            @endphp

                <br>
                <p style="margin-left:60px;">Cotizacion: <b>SQ-{{ $quote->id }}</b></p>
                <table border="1" >
                    <tr>
                        <th style="width:30%" >Imagen de Referencia</th>
                        <th style="width:70%" colspan="3">Descripción 
                    </th>
                    </tr>
                    <tr>
                        <td rowspan="6" style="width:30%"> 

                        @if($productLogo != null || $productLogo != '')
                            <center><img style="width:200px; height:240px; object-fit:contain;" src="{{$filePath}}" alt=""></center>
                        @else
                            <center><img style="width:200px; height:240px; object-fit:contain;" src="data:image/png;base64,{{$image64}}" alt=""></center>
                        @endif
                        </td>
                        <td colspan="3" style="width:70%; padding:2px;">{{ $productName }}</td>
                        
                    </tr>
                    <tr>
                        <th colspan="1" style="width:35%; padding:2px;">Tecnica de Personalizacion </th>
                        <th colspan="2" style="width:35%; padding:2px;" >Detalle de la Personalizacion </th>
                    </tr>
                    <tr>
                        <td colspan="1" style="width:35%">{{ isset($quoteTechnique->technique)? $quoteTechnique->technique :  '' }} </td>
                        <td colspan="2" style="width:35%">
                            <p> <b>Material: </b>  {{ isset($quoteTechnique->material)? $quoteTechnique->material : ''  }} </p>
                            <p> <b>Tamaño: </b>  {{ isset($quoteTechnique->size)? $quoteTechnique->size : '' }} </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="width:10% ;"><center><b>Tiempo de Entrega: 10 días hábiles</b> </center>  </td>
                    </tr>
                    <tr>
                        <th colspan="1">Cantidad</th>
                        <th colspan="1">Precio Unitario</th>
                        <th colspan="1">Precio total</th>
                    </tr>
                    <tr>
                        <td colspan="1"> {{ $product->cantidad}} piezas</td>
                        <td colspan="1"> {{ $product->precio_unitario * 1.2}} </td>
                        <td colspan="1"> {{ $product->precio_total * 1.2 }}</td>
                    </tr>
                </table>
            <br>
        @endforeach
          
           
    </div>

    <div style="margin-left:30px;">
        <p> Condiciones:</p>
        <ul>
            <li>Condiciones de pago acordadas en el contrato</li>
            <li>Precios unitarios mostrados antes de IVA</li>
            <li>Precios mostrados en pesos mexicanos (MXP)</li>
            <li>Una vez realizada la cotización, la entrega de los productos se realizará en un plazo estimado de 10 días hábiles.</li>
            <li>Antes de generar la cotización, le invitamos a verificar la disponibilidad actual de stock para garantizar la prontitud en el cumplimiento de su pedido.</li>
        </ul>
    </div>

</body>

</html>
