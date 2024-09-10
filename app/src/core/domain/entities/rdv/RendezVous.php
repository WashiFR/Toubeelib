<?php

namespace toubeelib\core\domain\entities\rdv;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\dto\RdvDTO;

class RendezVous extends Entity
{

    protected string $ID_praticien;
    protected string $ID_patient;
    protected ?Specialite $specialite = null;
    protected string $date;

    public function __construct(string $ID_praticien, string $ID_patient, Specialite $specialite, string $date)
    {
        $this->ID_praticien = $ID_praticien;
        $this->ID_patient = $ID_patient;
        $this->specialite = $specialite;
        $this->date = $date;
    }

    public function toDTO(): RdvDTO
    {
        return new RdvDTO($this);
    }

}