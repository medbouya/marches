<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'minimum_amount_to_audit',
        'threshold_exclusion',
        'market_type_id',
    ];

    public function marketTypes()
    {
        return $this->belongsToMany(MarketType::class);
    }
}
