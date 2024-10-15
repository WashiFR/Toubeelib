<?php

namespace toubeelib\infrastructure\repositories;

use PDO;
use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\User;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;

class PDOUserRepository implements UserRepositoryInterface
{

    private array $users = [];

    public function __construct()
    {
        $dbCredentials = parse_ini_file(__DIR__ . 'toubeelibdb.env.dist');
        $data = new PDO('postgres:host=localhost;dbname=toubeelib', $dbCredentials["POSTGRES_USER"], $dbCredentials["POSTGRES_PASSWORD"]);
        $stmt = $data->query('SELECT * FROM USERS');
        $users = $stmt->fetchAll();
        foreach ($users as $user) {
            $this->users[$user['ID']] = new User($user['ID'], $user['email'], $user['password']);
        }

    }

    public function getUserById(string $id): User
    {
        if (!isset($this->users[$id])) {
            throw new RepositoryEntityNotFoundException('User not found');
        }
        return $this->users[$id];
    }

    public function save(User $user): string
    {
        $this->users[$user->getId()] = $user;
        return $user->getId();
    }

    public function getUserByEmail(string $email): User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        throw new RepositoryEntityNotFoundException('User not found');
    }
}