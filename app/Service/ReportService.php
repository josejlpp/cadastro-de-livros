<?php

namespace App\Service;

use App\Models\ViewLivros;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;

class ReportService
{
    private PdfReport $report;

    public function __construct()
    {
        $this->report = new PdfReport();
    }
    public function generateReport($requestReport): void
    {
        $livros  = ViewLivros::orderBy('Autores');

        $colunas = [
            'Título' => 'Titulo',
            'Autores' => 'Autores',
            'Editora' => 'Editora',
            'Pub.' => 'AnoPublicacao',
            'Ed.' => 'Edicao',
            'Valor' => 'Valor'
        ];

        $meta = [
            'Data de geração' => date('d/m/Y H:i:s'),
            'Total de livros' => $livros->count()
        ];

        $this->report->of('Relatório de Livros por Autor', $meta, $livros, $colunas)
            ->setOrientation('landscape')
            ->groupBy('Autores')
            ->editColumn('Valor',
                [
                    'displayAs' => function ($result) {
                        return 'R$ ' . number_format($result->Valor, 2, ',', '.');
                    },
                    'class' => 'wider-100'
                ]
            )
            ->editColumn('Ed.',
                [
                    'class' => 'wider-50'
                ]
            )
            ->setCss([
                '.wider-100' => 'width:100px',
                '.wider-80' => 'width:50px',
            ])
            ->store("reports/{$requestReport->file_name}.pdf");

        $requestReport->update(['status' => 'completed']);
    }
}
