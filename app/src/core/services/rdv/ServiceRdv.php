<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\RdvDTO;
use toubeelib\core\dto\InputRdvDTO;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;

class ServiceRdv implements ServiceRdvInterface
{

    private PraticienRepositoryInterface $praticienRepository;
    private RdvRepositoryInterface $rdvRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository, RdvRepositoryInterface $rdvRepository)
    {
        $this->praticienRepository = $praticienRepository;
        $this->rdvRepository = $rdvRepository;
    }

    public function getRdvById(string $id): RdvDTO
    {
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
            return new RdvDTO($rdv);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServiceRdvInvalidDataException('invalid Rdv ID');
        }
    }

    public function creerRendezVous(InputRdvDTO $rdv): RdvDTO
    {
        if ($this->praticienRepository->getPraticienById($rdv->getID_praticien()) === null) {
            throw new ServiceRdvInvalidDataException('invalid praticien ID');
        } elseif ($this->praticienRepository->getPraticienById($rdv->getID_praticien())->getSpecialite() === $rdv->getSpecialite()) {
            throw new ServiceRdvInvalidDataException('invalid specialite');
        }
        // TODO : vérifier si le praticien est disponible à la date demandée

        $rdv = $this->rdvRepository->creerRendezVous($rdv);
        return new RdvDTO($rdv);
    }

    public function annulerRendezVous(string $id): void
    {
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
            $rdv->annuler();
            $this->rdvRepository->updateRdv($rdv);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServiceRdvInvalidDataException('invalid Rdv ID');
        }
    }
}