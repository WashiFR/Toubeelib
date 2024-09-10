<?php

namespace toubeelib\core\domain\entities\praticien;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\PraticienDTO;

class Praticien extends Entity
{
    // Constantes
    public const DUREE_RDV = 30; // durée d'un rendez-vous en minutes
    public const HEURE_DEBUT = 8; // heure de début de la journée de travail
    public const HEURE_FIN = 17; // heure de fin de la journée de travail
    public const HEURE_PAUSE_DEBUT = 12; // heure de début de la pause déjeuner
    public const HEURE_PAUSE_FIN = 13; // heure de fin de la pause déjeuner
    public const JOURS_TRAVAIL = [1, 2, 3, 4, 5]; // jours de travail : lundi à vendredi

    // Propriétés
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected ?Specialite $specialite = null; // version simplifiée : une seule spécialité

    public function __construct(string $nom, string $prenom, string $adresse, string $tel)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }

    public function setSpecialite(Specialite $specialite): void
    {
        $this->specialite = $specialite;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function toDTO(): PraticienDTO
    {
        return new PraticienDTO($this);
    }
}