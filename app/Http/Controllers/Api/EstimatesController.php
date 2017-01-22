<?php

namespace App\Http\Controllers\Api;

use App\Estimates\Estimate;
use App\Estimates\EstimateRepository;
use App\Estimates\Validators\Update;
use Sorskod\Larasponse\Larasponse;
use App\Exceptions\EstimateValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EstimatesController extends BaseController
{
    /**
     * @var Request request
     */
    private $request;
    /**
     * @var EstimateRepository estimateRepository
     */
    private $estimateRepository;
    /**
     * @var Larasponse
     */
    private $response;

    public function __construct(Request $request, EstimateRepository $estimateRepository, Larasponse $response)
    {
        parent::__construct();
        $this->request = $request;
        $this->estimateRepository = $estimateRepository;
        $this->response = $response;
    }

    public function deleteEstimate(Estimate $estimate)
    {
        if (! $this->estimateBelongsToUser($estimate, $this->user)) {
            throw new NotFoundHttpException();
        }

        $this->estimateRepository->remove($estimate);

        return $this->respondSuccess();
    }

    public function getEstimate(Estimate $estimate)
    {
        if (! $this->estimateBelongsToUser($estimate, $this->user)) {
            throw new NotFoundHttpException();
        }

        return $this->response->item($estimate, new \App\Http\Transformers\Estimate());
    }

    public function updateEstimate(Estimate $estimate)
    {
        if (! $this->estimateBelongsToUser($estimate, $this->user)) {
            throw new NotFoundHttpException();
        }

        try {
            $this->estimateRepository->update($estimate, $this->request->all());
        } catch (EstimateValidationException $e) {
            return $this->respondError(['message' => $e->getMessage()], 400);
        }

        return $this->respondSuccess();
    }
}
