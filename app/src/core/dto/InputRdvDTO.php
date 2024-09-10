<?php

namespace toubeelib\core\dto;

use toubeelib\core\dto\DTO;

class InputRdvDTO extends DTO
{

    protected string $ID_praticien;
    protected string $ID_patient;
    protected string $specialite;
    protected string $date;

    public function __construct(string $ID_praticien, string $ID_patient, string $specialite, string $date)
    {
        $this->ID_praticien = $ID_praticien;
        $this->ID_patient = $ID_patient;
        $this->specialite = $specialite;
        $this->date = $date;
    }

    public function getID_praticien(): string
    {
        return $this->ID_praticien;
    }

    public function getID_patient(): string
    {
        return $this->ID_patient;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

}