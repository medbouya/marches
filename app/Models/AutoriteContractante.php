<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoriteContractante extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'contact_person', 'is_exempted'];

    public function marketTypes()
    {
        return $this->belongsToMany(MarketType::class, 'autorite_market_type', 'autorite_contractante_id', 'market_type_id');
    }

    public function secteurs()
    {
        return $this->belongsToMany(Secteur::class);
    }

    // In AutoriteContractante model
    public function isExempted($marketType = null, $secteur = null)
    {
        if (!$this->is_exempted) {
            return false;
        }

        $hasExemptedMarketType = $marketType ? $this->marketTypes->contains('id', $marketType->id) : false;
        $hasExemptedSecteur = $secteur ? $this->secteurs->contains('id', $secteur->id) : false;

        // Return true if both marketType and secteur match the exempted entries
        return $hasExemptedMarketType && $hasExemptedSecteur;
    }

}
