<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'authority_contracting',
        'passation_mode',
        'year',
        'market_type_id',
    ];

    public function marketType()
    {
        return $this->belongsTo(MarketType::class);
    }
}
