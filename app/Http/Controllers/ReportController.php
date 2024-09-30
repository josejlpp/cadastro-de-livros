<?php

namespace App\Http\Controllers;

use App\Events\ReportRequested;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::paginate(10);
        return view('reports.index', compact('reports'));
    }

    public function generate(Request $request)
    {

    }

    public function create()
    {
        $name = 'livros-' . date('d-m-Y-H-i-s');

        $report = Report::create([
            'status' => 'generating',
            'file_name' => "{$name}"
        ]);

        ReportRequested::dispatch($report);

        return redirect()->route('report.index')->with('success', 'Relatório em fila de processamento!');
    }

    public function download($fileName)
    {
        return response()->download(storage_path('app/private/reports/' . $fileName . '.pdf'));
    }

    public function delete(Report $report)
    {
        if (Storage::exists("reports/{$report->file_name}.pdf")) {
            Storage::delete("reports/{$report->file_name}.pdf");
        }

        $report->delete();

        return redirect()->route('report.index')->with('success', 'Relatório deletado com sucesso!');
    }
}
