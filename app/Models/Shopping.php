<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'iva_by_item',
        'address_id',
        'show_total',
        'logo',
        "direccion",
        'status',
    ];

    public function shoppingUpdate()
    {
        return $this->hasMany(ShoppingUpdate::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function latestShoppingUpdate()
    {
        return $this->hasOne(ShoppingUpdate::class)->latestOfMany();
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function products()
    {
        return $this->hasMany(ShoppingProduct::class, 'shopping_id');
    }


}
