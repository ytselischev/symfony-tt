<?php

namespace App\Controller\Rest;


use App\Service\ReservationService;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ReservationController extends FOSRestController
{
    /**
     * @var ReservationService
     */
    private $reservationService;

    /**
     * ReservationController constructor.
     * @param ReservationService $reservationService
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Creates an reservation
     * @Rest\Post("/reservation")
     * @Rest\RequestParam(name="table_id",requirements="\d+", nullable=true, description="Table identifier")
     * @Rest\RequestParam(name="date",requirements="\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])", nullable=false, description="Reservation date")
     * @Rest\RequestParam(name="from",requirements="([0-1]?[0-9]|2[0-3]):[0-5][0-9]", nullable=false, description="Reservation from time")
     * @Rest\RequestParam(name="to",requirements="([0-1]?[0-9]|2[0-3]):[0-5][0-9]", nullable=false, description="Reservation to time")
     *
     * @param ParamFetcher $paramFetcher
     * @return View
     */
    public function post(ParamFetcher $paramFetcher): View
    {
        $this->reservationService->setRequestParams($paramFetcher->all());

        if (!$this->reservationService->checkMinTime()) {
            throw new BadRequestHttpException(
                "Minimum reservation time is 30 minutes"
            );
        }
        if (!$this->reservationService->checkDeskFree()) {
            throw new BadRequestHttpException(
                "No tables available for that date and time period"
            );
        }

        $price = $this->reservationService->addReservation();

        return View::create(['price' => round($price, 2)], Response::HTTP_CREATED);
    }

}