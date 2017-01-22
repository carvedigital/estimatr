<?php

namespace App\Http\Controllers\Estimates;

use App\Estimates\Estimate;
use App\Estimates\EstimateRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditorController extends Controller
{
    /**
     * @var Request request
     */
    private $request;
    /**
     * @var EstimateRepository estimateRepository
     */
    private $estimateRepository;

    public function __construct(Request $request, EstimateRepository $estimateRepository)
    {
        parent::__construct();
        $this->request = $request;
        $this->estimateRepository = $estimateRepository;
    }

    public function getEstimate(Estimate $estimate)
    {
        if (! $this->estimateBelongsToUser($estimate, $this->user)) {
            throw new NotFoundHttpException();
        }

        return view('estimates.editor')
            ->with('pageTitle', $estimate->name)
            ->with('estimate', $estimate);
    }
}
