<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'audit_status',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
