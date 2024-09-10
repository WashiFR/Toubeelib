<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\InputRdvDTO;
use toubeelib\core\dto\RdvDTO;

interface ServiceRdvInterface
{

    public function getRdvById(string $id): RdvDTO;
    public function creerRendezVous(InputRdvDTO $rdv): RdvDTO;
    public function annulerRendezVous(string $id): void;

}