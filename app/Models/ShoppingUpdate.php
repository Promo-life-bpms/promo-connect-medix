<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingUpdate extends Model
{
    use HasFactory;
    public $table = 'shopping_updates';

    protected $fillable = [
        'quote_id',
        'quote_information_id',
        'quote_discount_id',
        'type',
    ];

    public function shoppingProducts()
    {
        return $this->belongsToMany(ShoppingProduct::class, 'shopping_update_product', 'shopping_update_id', 'shopping_product_id');
    }

    public function shoppingInformation()
    {
        return $this->belongsTo(ShoppingInformation::class, 'shopping_information_id');
    }

    public function shoppingDiscount()
    {
        return $this->belongsTo(ShoppingDiscount::class, 'shopping_discount_id');
    }

}
