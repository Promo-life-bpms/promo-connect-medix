<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingTechnique extends Model
{
    use HasFactory;

    public $table = 'shopping_techniques';

    protected $fillable = [
        'quote_id',
        'material',
        'technique',
        'size',
    ];
}
