<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ReportServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportServiceInterface $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;
    }

    // Generalized report method to support multiple report types
    public function reports(Request $request): JsonResponse
    {
        $type = $request->query('type');

        switch ($type) {
            case 'clients':
                $report = $this->reportService->getClientsReport();
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        return response()->json($report);
    }
}
