<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentQuotesTechniques extends Model
{
    use HasFactory;
    public $table = 'current_quotes_techniques';  

    protected $fillable = [
        'current_quotes_details_id',
        'material',
        'technique',
        'size',
    ];

    public function currentQuoteDetail()
    {
        return $this->belongsTo(CurrentQuoteDetails::class, 'current_quotes_details_id');
    }
}
