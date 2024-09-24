<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\RdvDTO;
use toubeelib\core\dto\InputRdvDTO;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\services\rdv\ServiceRdvInterface;

class ServiceRdv implements ServiceRdvInterface
{
    //constantes
    public const DUREE_RDV = 30; // durée d'un rendez-vous en minutes
    public const HEURE_DEBUT = 8; // heure de début de la journée de travail
    public const HEURE_FIN = 17; // heure de fin de la journée de travail
    public const HEURE_PAUSE_DEBUT = 12; // heure de début de la pause déjeuner
    public const HEURE_PAUSE_FIN = 13; // heure de fin de la pause déjeuner
    public const JOURS_TRAVAIL = [1, 2, 3, 4, 5]; // jours de travail : lundi à vendredi

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

    public function getDisponibilitesPraticien(string $id): array
    {
        try{
        // On remplit un tableau qui contient tous les créneaux
        while ($date->format('N') < 6) {
            if ($date->format('N') === '7') {
                $date = $date->modify('+1 day');
                continue;
            }
            if ($date->format('H') < self::HEURE_DEBUT || $date->format('H') >= self::HEURE_FIN) {
                $date = $date->modify('+1 hour');
                continue;
            }
            if ($date->format('H') >= self::HEURE_PAUSE_DEBUT && $date->format('H') < self::HEURE_PAUSE_FIN) {
                $date = $date->modify('+1 hour');
                continue;
            }
            $disponibilites[] = $date;
            $date = $date->modify('+'.self::DUREE_RDV.' minutes');
        }

        // On retire les créneaux déjà occupés
        $rdvs = $this->rdvRepository->getRdvsByPraticienId($id);
        foreach ($rdvs as $rdv) {
            $date = $rdv->getDate();
            $key = array_search($date, $disponibilites);
            if ($key !== false) {
                unset($disponibilites[$key]);
            }
        }

        return $disponibilites;
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServiceRdvInvalidDataException('invalid Praticien ID');
        }
    }

    public function modifierSpecialiteRendezVous(string $id, string $specialite) : void{
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
            $rdv->setSpecialite($specialite);
            $this->rdvRepository->updateRdv($rdv);
        } catch (RepositoryEntityNotFoundException $e){
            throw new ServiceRdvInvalidDataException('invalid Rdv ID');
        }
    }

    public function modifierPatientRendezVous(string $id, string $ID_patient) : void{
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
            $rdv->setID_patient($ID_patient);
            $this->rdvRepository->updateRdv($rdv);
        } catch (RepositoryEntityNotFoundException $e){
            throw new ServiceRdvInvalidDataException('invalid Rdv ID');
        }
    }
}