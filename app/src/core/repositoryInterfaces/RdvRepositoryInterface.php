<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\InputRdvDTO;

interface RdvRepositoryInterface
{

    public function getRdvById(string $id): RendezVous;
    public function creerRendezVous(InputRdvDTO $rdv);
    public function updateRdv(RendezVous $rdv): void;

    public function getRdvsByPraticienId(string $id, \DateTime $fromDate, \DateTime $toDate): array;

}