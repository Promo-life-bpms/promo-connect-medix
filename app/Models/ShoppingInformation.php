<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingInformation extends Model
{
    use HasFactory;
    public $table = 'shopping_information';

    protected $fillable = [
        'name',
        'email',
        'landline',
        'cell_phone',
        'oportunity',
        'rank',
        'department',
        'information',
        'tax_fee',
        'shelf_life',
    ];

    public function shoppingProducts()
    {
        return $this->hasMany(ShoppingProduct::class);
    }

}
