<?php

namespace toubeelib\infrastructure\repositories;

use PDO;
use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\User;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;

class PDOUserRepository implements UserRepositoryInterface
{

    private PDO $pdo;

    private array $users = [];

    public function __construct()
    {
        $dbCredentials = parse_ini_file(__DIR__ . 'toubeelibdb.env.dist');
        $pdo = new PDO('postgres:host=localhost;dbname=toubeelib', $dbCredentials["POSTGRES_USER"], $dbCredentials["POSTGRES_PASSWORD"]);
        $stmt = $pdo->query('SELECT * FROM USERS');
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
        $this->pdo->insert('INSERT INTO USERS (ID, email, password, role) VALUES (:id, :email, :password, :role) ON DUPLICATE KEY UPDATE email = VALUES(email), password = VALUES(password), role = VALUES(role)', [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ]);

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