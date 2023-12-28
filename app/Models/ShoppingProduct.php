<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingProduct extends Model
{
    use HasFactory;
    public $table = 'shopping_products';

    protected $fillable = [
        'product',
        'technique',
        'prices_techniques',
        'color_logos',
        'costo_indirecto',
        "utilidad",
        'dias_entrega',
        'cantidad',
        'precio_unitario',
        'precio_total',
        'shopping_by_scales',
        'scales_info',
        'shopping_id',
    ];

}
