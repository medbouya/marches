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
        'cpmp_id',
        'secteur_id',
        'attributaire_id',
        'numero',
        'financement',
        'date_signature',
        'date_notification',
        'date_publication',
        'delai_execution',
    ];

    public function marketType()
    {
        return $this->belongsTo(MarketType::class);
    }

    public function modePassation()
    {
        return $this->belongsTo(ModePassation::class, 'passation_mode');
    }

    public function autoriteContractante()
    {
        return $this->belongsTo(AutoriteContractante::class, 'authority_contracting');
    }

    public function cpmp()
    {
        return $this->belongsTo(CPMP::class);
    }

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function attributaire()
    {
        return $this->belongsTo(Attributaire::class);
    }
}
