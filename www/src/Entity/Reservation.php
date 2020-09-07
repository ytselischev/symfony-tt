<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Desk", inversedBy="reservations")
     */
    private $desk;

    /**
     * @ORM\Column(name="date_start", type="datetime")
     */
    private $from;

    /**
     * @ORM\Column(name="date_end", type="datetime")
     */
    private $to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrom(): ?\DateTimeInterface
    {
        return $this->from;
    }

    public function setFrom(\DateTimeInterface $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?\DateTimeInterface
    {
        return $this->to;
    }

    public function setTo(\DateTimeInterface $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getDesk(): ?Desk
    {
        return $this->desk;
    }

    public function setDesk(?Desk $desk): self
    {
        $this->desk = $desk;

        return $this;
    }

}
