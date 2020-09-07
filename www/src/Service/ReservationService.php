<?php

namespace App\Service;

use App\Entity\Desk;
use App\Entity\Reservation;
use App\Repository\DeskRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;

//use \DateTime;

class ReservationService
{

    const MIN_TIME = 30;
    const COST_PER_HOUR = 300;

    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @var \DateTime
     */
    protected $to;

    /**
     * @var int
     */
    protected $tableId = 0;

    /**
     * @var array
     */
    protected $freeTables = [];

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * @var DeskRepository
     */
    private $deskRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ReservationService constructor.
     * @param ReservationRepository $reservationRepository
     * @param DeskRepository $deskRepository
     */
    public function __construct(
        ReservationRepository $reservationRepository,
        DeskRepository $deskRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->deskRepository = $deskRepository;
        $this->entityManager = $entityManager;
    }

    public function setRequestParams(array $data)
    {
        $this->from = new \DateTime($data['date'].' '.$data['from'].':00');
        $this->to = new \DateTime($data['date'].' '.$data['to'].':59');
        $this->tableId = (int)$data['table_id'];

        if (!empty($this->tableId)) {
            $desk = $this->deskRepository->find($this->tableId);
            if (empty($desk)) {
                throw new BadRequestHttpException(
                    "No table with given identifier available"
                );
            }
        }
    }

    public function addReservation(): float
    {
        $reservation = new Reservation();
        $reservation->setDesk($this->deskRepository->find($this->tableId));
        $reservation->setFrom($this->from);
        $reservation->setTo($this->to);
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return (self::COST_PER_HOUR * $this->getMinutesFromTimes() / 60);
    }

    public function checkMinTime(): bool
    {
        return $this->getMinutesFromTimes() >= self::MIN_TIME;
    }

    public function checkDeskFree(): bool
    {
        if (!empty($this->tableId)) {
            $reservedDesks = $this->reservationRepository->getReservedIds($this->from, $this->to, $this->tableId);
            if (count($reservedDesks) == 0) {
                return true;
            }
        } else {
            $reservedDesks = $this->reservationRepository->getReservedIds($this->from, $this->to);
            if (count($reservedDesks) < Desk::TOTAL) {
                $freeDesks = array_diff(range(1, Desk::TOTAL), $reservedDesks);
                $this->tableId = array_shift($freeDesks);
                return true;
            }
        }

        return false;
    }

    protected function getMinutesFromTimes(): int
    {
        $interval = $this->from->diff($this->to);
        $minutes = $interval->h * 60;
        $minutes += $interval->i;

        return $interval->invert === 0 ? $minutes : 0;
    }
}