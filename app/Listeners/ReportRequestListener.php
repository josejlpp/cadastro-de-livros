<?php

namespace App\Listeners;

use App\Events\ReportRequested;
use App\Service\ReportService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ReportRequestListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $requestReport = $event->reportRequest;
        $service = new ReportService();

        try {
            $service->generateReport($requestReport);
        } catch (Exception $e) {
            $requestReport->update(['status' => 'failed']);
            Log::error($e->getMessage());
        }
    }

    public function subscribe(): array
    {
        return [
            ReportRequested::class => 'handle',
        ];
    }
}
