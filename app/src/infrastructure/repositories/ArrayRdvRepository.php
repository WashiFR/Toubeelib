<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\InputRdvDTO;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayRdvRepository implements RdvRepositoryInterface
{
    private array $rdvs = [];

    public function __construct() {
            $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
            $r1->setID('r1');
            $r2 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 10:00'));
            $r2->setID('r2');
            $r3 = new RendezVous('p2', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:30'));
            $r3->setID('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }


    public function getRdvById(string $id): RendezVous
    {
        if (!isset($this->rdvs[$id])) {
            throw new RepositoryEntityNotFoundException('RendezVous not found');
        }
        return $this->rdvs[$id];
    }

    public function creerRendezVous(InputRdvDTO $rdv)
    {
        $r = new RendezVous($rdv->getID_praticien(), $rdv->getID_patient(), $rdv->getSpecialite(), \DateTimeImmutable::createFromFormat('Y-m-d H:i', $rdv->getDate()));
        $r->setID(Uuid::uuid4()->toString());
        $this->rdvs[$r->getID()] = $r;
        return $r;
    }

    public function updateRdv(RendezVous $rdv): void
    {
        $this->rdvs[$rdv->getID()] = $rdv;
    }

    public function getRdvsByPraticienId(string $id, \DateTime $fromDate, \DateTime $toDate): array
    {
        $rdvs = [];
        foreach ($this->rdvs as $rdv) {
            if ($rdv->getID_praticien() === $id && $rdv->getDate() >= $fromDate && $rdv->getDate() <= $toDate) {
                $rdvs[] = $rdv;
            }
        }
        return $rdvs;
    }
}