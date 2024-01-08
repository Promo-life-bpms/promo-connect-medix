@component('mail::message')

# Cotizacion 

---

[BH Trademarket S.A. de C.V.](https://trademarket.com.mx/)
San Andrés Atoto 155A Naucalpan de Juárez, Méx. C.P. 53550  
Tel. +52(55) 6806 4616 

Fecha: **{{ $date }}**

---

<!-- @foreach($quotes as $quote)
 -->
---

    @php
        try {
            if ($quotes->logo != null) {
                $logo = public_path('/storage/logos/' . $quotes->logo);
                $image64 = base64_encode(file_get_contents($logo));
            } else {
                $logo = public_path('img/default.jpg');
                $image64 = base64_encode(file_get_contents($logo));
            }
        } catch (\Exception $e) {
            $logo = public_path('img/default.jpg');
            $image64 = base64_encode(file_get_contents($logo));
        }

        $quoteTechnique = \App\Models\QuoteTechniques::where('quotes_id',$quotes->id)->get()->last();

        $product = \App\Models\QuoteProducts::where('id', $quotes->id)->get()->last();
        $productData = json_decode($product->product);

        $productName = isset($productData->name) ? $productData->name : 'Nombre no disponible';
        
            
    @endphp

#Cotizacion: **SQ-{{ $quotes->id }}**

{{-- ![Imagen de Referencia](data:image/png;base64,{{$image64}})
 --}}
## Descripción
{{ $productName }}

### Técnica de Personalización
| Descripción                      | {{ isset($quotes->currentQuotesTechniques->technique) ? $quotes->currentQuotesTechniques->technique : '' }} |
|----------------------------------|-------------------------------------------------------------------------------------------------------------------|
| Material                         | {{ isset($quoteTechnique->material) ? $quoteTechnique->material : '' }}                                           |
| Tamaño                           | {{ isset($quoteTechnique->size) ? $quoteTechnique->size : '' }}                                                   |

**Tiempo de Entrega:** 10 días hábiles

### Detalles del Producto
| Cantidad         | Precio Unitario  | Precio total      |
|------------------|------------------|-------------------|
| {{ $product->cantidad }} piezas | {{ $product->precio_unitario }} | {{ $product->precio_total }} |

---

<!-- @endforeach -->

---

Condiciones:
- Condiciones de pago acordadas en el contrato
- Precios unitarios mostrados antes de IVA
- Precios mostrados en pesos mexicanos (MXP)

@endcomponent