<?php

namespace toubeelib\infrastructure\repositories;

use PDO;
use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    const SPECIALITES = [
        'A' => [
            'ID' => 'A',
            'label' => 'Dentiste',
            'description' => 'Spécialiste des dents'
        ],
        'B' => [
            'ID' => 'B',
            'label' => 'Ophtalmologue',
            'description' => 'Spécialiste des yeux'
        ],
        'C' => [
            'ID' => 'C',
            'label' => 'Généraliste',
            'description' => 'Médecin généraliste'
        ],
        'D' => [
            'ID' => 'D',
            'label' => 'Pédiatre',
            'description' => 'Médecin pour enfants'
        ],
        'E' => [
            'ID' => 'E',
            'label' => 'Médecin du sport',
            'description' => 'Maladies et trausmatismes liés à la pratique sportive'
        ],
    ];

    private array $praticiens = [];

    public function __construct() {
        $dataCredentials = parse_ini_file(__DIR__ . 'toubeelibdb.env.dist');
        $data = new PDO('postgres:host=localhost;dbname=toubeelib', $dataCredentials["POSTGRES_USER"], $dataCredentials["POSTGRES_PASSWORD"]);
        $stmt = $data->query('SELECT * FROM USERS where role = 10');
        $praticiens = $stmt->fetchAll();
        foreach ($praticiens as $praticien) {
            $this->praticiens[$praticien['ID']] = new Praticien($praticien['ID'], $praticien['nom'], $praticien['prenom'], $praticien['specialite']);
        }
    }
    public function getSpecialiteById(string $id): Specialite
    {

        $specialite = self::SPECIALITES[$id] ??
            throw new RepositoryEntityNotFoundException("Specialite $id not found") ;

        return new Specialite($specialite['ID'], $specialite['label'], $specialite['description']);
    }

    public function save(Praticien $praticien): string
    {
        // TODO : prévoir le cas d'une mise à jour - le praticient possède déjà un ID
		$ID = Uuid::uuid4()->toString();
        $praticien->setID($ID);
        $this->praticiens[$ID] = $praticien;
        return $ID;
    }

    public function getPraticienById(string $id): Praticien
    {
        $praticien = $this->praticiens[$id] ??
            throw new RepositoryEntityNotFoundException("Praticien $id not found");

        return $praticien;
    }

}