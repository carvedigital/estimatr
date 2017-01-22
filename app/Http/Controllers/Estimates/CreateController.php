<?php

namespace App\Http\Controllers\Estimates;

use App\Estimates\EstimateFactory;
use App\Estimates\EstimateRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
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

    public function getCreate()
    {
        return view('estimates.create')
                    ->with('pageTitle', 'Create A New Estimate');
    }

    public function postCreate()
    {
        $estimate = EstimateFactory::createFromUi($this->user, $this->request->only('name', 'rate'));

        return redirect()->route('get-estimate', [$estimate->id]);
    }

    public function getList()
    {
        if (! $this->user->hasEstimates()) {
            return redirect()->route('new-estimate');
        }

        $estimates = $this->estimateRepository->getByUser($this->user);

        return view('estimates.list')
                ->with('pageTitle', 'Your Estimates')
                ->with('estimates', $estimates);
    }
}
