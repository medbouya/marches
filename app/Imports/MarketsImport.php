<?php

namespace App\Imports;

use App\Models\Attributaire;
use App\Models\AutoriteContractante;
use App\Models\CPMP;
use App\Models\Market;
use App\Models\MarketType;
use App\Models\ModePassation;
use App\Models\Secteur;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarketsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function getMarketType($name) {
        $name = trim(strtolower($name));
        $marketType = MarketType::whereRaw('lower(name) = ?', [$name])->first();
        if (!$marketType) 
            $marketType = MarketType::create(['name' => $name]);
        return $marketType->id;
    }

    public function getPassationModeId($name) {
        $name = trim(strtolower($name));
        $modePassation = ModePassation::whereRaw('lower(name) = ?', [$name])->first();
        if (!$modePassation) 
            $modePassation = ModePassation::create(['name' => $name]);;
        return $modePassation->id;
    }

    public function getAuthorityId($name) {
        $name = trim(strtolower($name));
        $autoriteContractante = AutoriteContractante::whereRaw('lower(name) = ?', [$name])->first();;
        if (!$autoriteContractante) 
            $autoriteContractante = AutoriteContractante::create(['name' => $name]);
        return $autoriteContractante->id;
        
    }

    public function getCpmpId($name) {
        $name = trim(strtolower($name));
        $cpmp = CPMP::whereRaw('lower(name) = ?', [$name])->first();;
        if (!$cpmp) 
            $cpmp = CPMP::create(['name' => $name]);
        return $cpmp->id;
    }

    public function getSecteurId($name) {
        $name = trim(strtolower($name));
        $secteur = Secteur::whereRaw('lower(name) = ?', [$name])->first();;
        if (!$secteur) 
            $secteur = Secteur::create(['name' => $name]);
        return $secteur->id;
    }

    public function getAttributaireId($name) {
        $name = trim(strtolower($name));
        $attributaire = Attributaire::whereRaw('lower(name) = ?', [$name])->first();;
        if (!$attributaire) 
            $attributaire = Attributaire::create(['name' => $name]);
        return $attributaire->id;
    }
    
    public function model(array $row)
    {
        // Importing new data and searching for corresponding foreign keys
        return new Market(
            [
                'title' => $row['objet'],
                'amount' => $row['montant'],
                'year' => $row['annee'],
                'market_type_id' => $this->getMarketType($row['type_marche']),
                'passation_mode' => $this->getPassationModeId($row['mode_passation']),
                'authority_contracting' => $this->getAuthorityId($row['autorite']),
                'cpmp_id' => $this->getCpmpId($row['cpmp']),
                'secteur_id' => $this->getSecteurId($row['secteur']),
                'attributaire_id' => $this->getAttributaireId($row['attributaire']),
                'numero' => $row['numero'],
                'financement' => $row['financement'] ?? null,
                'date_signature' => $this->transformDate($row['date_signature']),
                'date_notification' => $this->transformDate($row['date_notification']),
                'date_publication' => $this->transformDate($row['date_publication']),
                'delai_execution' => $row['delai_execution'],
            ]);
    }

    /**
     * Converts Excel date format to a database compatible format.
     *
     * @param mixed $value The Excel date value.
     * @return string|null Formatted date string or null.
     */
    protected function transformDate($value) {
        if (empty($value)) {
            return null;
        }
    
        try {
            // Assuming the date is in 'DD/MM/YYYY' format, convert it to 'YYYY-MM-DD'
            $date = Carbon::createFromFormat('d/m/Y', $value);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return null; // Or handle the exception as needed
        }
    
        return $date->format('Y-m-d'); // Format the date as required by your database
    }

}