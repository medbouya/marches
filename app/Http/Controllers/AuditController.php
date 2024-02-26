<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::select('audits.*')
            ->join('markets', 'audits.market_id', '=', 'markets.id')
            ->orderBy('markets.amount', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('audits.index', compact('audits'));
    }

    public function auditedMarkets()
    {
        $audits = Audit::all();
        foreach ($audits as $audit) {
            $audit->audit_status = true;
            $audit->save();
        }

        return redirect()->route('audits.index')->with(
            'success',
            'L\'audit des marchés a été effectué avec succès.'
        );
    }

    public function cancelAudit()
    {
        Audit::query()->delete();

        return redirect()->route('audits.index')->with(
            'success',
            'L\'audit des marchés a été annulé avec succès.'
        );
    }

    public function exportAuditsToExcel()
    {
        $audits = Audit::all();
        return Excel::download(
            new class($audits) implements FromCollection, WithHeadings
            {
                private $data;

                public function __construct($data)
                {
                    $this->data = $data;
                }

                public function collection()
                {
                    $this->data->load([
                        'market'
                    ]);

                    $transformed = $this->data->map(function ($item) {
                        return [
                            'Numéro' => $item->market->numero,
                            'Année' => $item->market->year,
                            'Objet' => $item->market->title,
                            'CPMP' => strtoupper($item->market->CPMP->name) ?? 'N/A',
                            'Autorité contractante' => strtoupper(
                                $item->market->autoriteContractante->name
                            ) ?? 'N/A',
                            //'Type de marché' => strtoupper($item->marketType->name) ?? 'N/A',
                            'Mode de Passation' => strtoupper(
                                $item->market->modePassation->name
                            ) ?? 'N/A',
                            'Secteur' => strtoupper($item->market->secteur->name) ?? 'N/A',
                            'Montant' => $item->market->amount,
                            'Financement' => $item->market->financement ?? 'N/A',
                            'Attributaire' => strtoupper($item->market->attributaire->name) ?? 'N/A',
                            'Date de signature' => $item->market->date_signature,
                            'Date de notification' => $item->market->date_notification,
                            'Date de publication' => $item->market->date_publication,
                            'Délai d\'exécution' => $item->market->delai_execution,
                            'Statut' => $item->audit_status ? 'Audité' : 'Non audité',

                        ];
                    });

                    return $transformed;
                }

                public function headings(): array
                {
                    return [
                        "Numéro", "Année", "Objet", "CPMP", "Autorité contractante",
                        "Mode de passation", "Secteur",
                        "Montant", "Financement", "Attributaire",
                        "Date de signature", "Date de notification",
                        "Date de publication", "Délai d'exécution", "Statut"
                    ];
                }
            },
            'markets.xlsx'
        );
    }
}
