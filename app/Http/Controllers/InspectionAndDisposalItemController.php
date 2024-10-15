<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Inspection;
use App\Models\Disposal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Services\ImageService;
use App\UseCases\InspectionAndDisposalItem\ScheduledInspectionsUseCase;
use App\UseCases\InspectionAndDisposalItem\HistoryInspectionsUseCase;
use App\UseCases\InspectionAndDisposalItem\ScheduledDisposalUseCase;
use App\UseCases\InspectionAndDisposalItem\HistoryDisposalUseCase;


class InspectionAndDisposalItemController extends Controller
{
    protected $imageService;
    protected $scheduledInspectionsUseCase;
    protected $historyInspectionsUseCase;
    protected $scheduledDisposalUseCase;
    protected $historyDisposalUseCase;
    
    public function __construct(
        ImageService $imageService,
        ScheduledInspectionsUseCase $scheduledInspectionsUseCase,
        HistoryInspectionsUseCase $historyInspectionsUseCase,
        ScheduledDisposalUseCase $scheduledDisposalUseCase,
        HistoryDisposalUseCase $historyDisposalUseCase
    ){
        $this->imageService = $imageService;
        $this->scheduledInspectionsUseCase = $scheduledInspectionsUseCase;
        $this->historyInspectionsUseCase = $historyInspectionsUseCase;
        $this->scheduledDisposalUseCase = $scheduledDisposalUseCase;
        $this->historyDisposalUseCase = $historyDisposalUseCase;
    }

    public function index()
    {
        Gate::authorize('staff-higher');

        $scheduledInspections = $this->scheduledInspectionsUseCase->handle();
        $historyInspections = $this->historyInspectionsUseCase->handle();
        $scheduledDisposals = $this->scheduledDisposalUseCase->handle();
        $historyDisposals = $this->historyDisposalUseCase->handle();

        return Inertia::render('InspectionAndDisposalItems/Index', [
            'scheduledInspections' => $scheduledInspections,
            'scheduledDisposals' => $scheduledDisposals,
            'historyInspections' => $historyInspections,
            'historyDisposals' => $historyDisposals,
        ]); 
    }
}
