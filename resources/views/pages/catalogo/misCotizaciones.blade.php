@extends('layouts.cotizador')

@section('content')
    
    <div class="container mx-auto w-full p-8">
    <style>
        tr:nth-child(even) {
        background-color: #fafafa;
        }
    </style>
        @php
            $totalGeneral = 0;
            if($quotes != null){
                foreach ($quotes as $quote){
                    $product = \App\Models\QuoteProducts::where('id', $quote->id)->get()->last();
                    $totalGeneral += intval($product->precio_total);
                }
            }
           
        @endphp

        @if(session('message'))
            <div class="bg-green-500 text-white p-4 mb-4">
                <p class="text-lg">¡Éxito! Se ha iniciado el proceso de compra de tu producto, puedes checar el proceso en la sección <b>MIS COMPRAS</b> .</p>
            </div>
        @endif
        <h1 class=" mt-8 text-2xl font-semibold mb-8"> Mis Cotizaciones</h1> 

        <div class="flex">
            <div class="w-1/2 mr-8">
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">N° de cotización:</h2>
                    <p class="text-bold text-4xl">{{count($quotes) }}</p>
                </div>
            </div>
        
            <div class="w-1/2">
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">Total: </h2>
                    <p class="text-bold text-4xl">$ {{ number_format($totalGeneral, 2, '.', ',') }}</p>
                </div>
            </div>
        
            <div class="w-1/2">
                
            </div>
        
            <div class="w-1/2">
                
            </div>
        </div>


        <br>
        <div class="w-full">
            <table class="table-auto">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th style="width:5%;">Cotizacion</th>
                        <th style="width:5%;">Logo</th>
                        <th style="width:10%;">Producto</th>
                        <th style="width:10%;">Tecnica</th>
                        <th style="width:20%;">Detalles</th>
                        <th style="width:10%;">Tiempo de entrega</th>
                        <th style="width:10%;">Cantidad</th>
                        <th style="width:10%;">Precio unitario</th>
                        <th style="width:10%;">Total</th>
                        <th style="width:10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotes as $quote)
                        @php
                           
                            $product = \App\Models\QuoteProducts::where('id', $quote->id)->get()->last();
                            $productData = json_decode($product->product);

                            $productName = isset($productData->name) ? $productData->name : 'Nombre no disponible';
                            $totalGeneral += intval($product->precio_total);
                        @endphp
                        
                        <tr class="border">
                            <td class="text-center"><b>SQ-{{$quote->id}}</b></td>
                            <td class="text-center">
                                @if($quote->logo == null || $quote->logo == '')
                                    Sin logo
                                @else
                                    <img class="w-20" src="/storage/logos/{{$quote->logo}}" alt="logo">
                                @endif
                            </td>
                            <td class="text-center">{{$productName }}</td>
                            <td class="text-center">{{isset($quote->quoteTechniques->technique)? $quote->quoteTechniques->technique : ''}}</td>
                            <td>
                                <p><b>Material: </b>  {{isset($quote->quoteTechniques->material)? $quote->quoteTechniques->material: ''}} </p>
                                <p><b>Tamaño: </b>  {{isset($quote->quoteTechniques->size)?  $quote->quoteTechniques->size: '' }} </p>
                              </td>
                            <td class="text-center">{{ $product->dias_entrega}} dias</td>
                            <td class="text-center"> {{ $product->cantidad}} piezas</td>
                            <td class="text-center"> <b>$ {{ $product->precio_unitario}} </b>   </td>
                            <td class="text-center"> <b>$ {{ number_format($product->precio_total, 2, '.', ',') }} </b>  </td>
                            <td class="text-center"> 
                                <form method="POST" action="{{ route('downloadPDF') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $quote->id }}">
                                    <button type="submit" class="w-full bg-primary hover:bg-primary text-white font-bold p-2 rounded text-sm">
                                        Descargar cotizacion
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('compras.realizarcompra') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $quote->id }}">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold p-2 rounded text-sm">
                                        Confirmar compra
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
           
            <div class="flex justify-end">
                <div class="flex space-x-2 mt-2"> 
                {{ $quotes->links() }}
                </div>
            </div>
            
        </div>
        <br><br>
        
    </div>
@endsection
