<?php

namespace Infrastructure\persistence;

use Domain\entities\User;
use Domain\repositories\UserRepositoryInterface;
use Domain\value_objects\Email;
use Infrastructure\database\DatabaseConnection;
use PDO;

/**
 * Implementação do repositório de usuários usando PDO
 */
class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->pdo = $dbConnection->getPdo();
    }

    public function findByEmail(Email $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT id, email, password_hash, created_at FROM users WHERE email = ?");
        $stmt->execute([$email->getValue()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapToEntity($row);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT id, email, password_hash, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapToEntity($row);
    }

    public function save(User $user): User
    {
        if ($user->getId() === null) {
            // Insert
            $stmt = $this->pdo->prepare("INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, ?)");
            $stmt->execute([
                $user->getEmail()->getValue(),
                $user->getPasswordHash(),
                $user->getCreatedAt()->format('Y-m-d H:i:s')
            ]);
            
            $id = (int)$this->pdo->lastInsertId();
            return new User(
                $user->getEmail(),
                $user->getPasswordHash(),
                $id,
                $user->getCreatedAt()
            );
        } else {
            // Update (se necessário no futuro)
            $stmt = $this->pdo->prepare("UPDATE users SET email = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([
                $user->getEmail()->getValue(),
                $user->getPasswordHash(),
                $user->getId()
            ]);
            
            return $user;
        }
    }

    public function emailExists(Email $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
        $stmt->execute([$email->getValue()]);
        $result = $stmt->fetch();
        
        return ($result['count'] ?? 0) > 0;
    }

    private function mapToEntity(array $row): User
    {
        $createdAt = $row['created_at'] 
            ? \DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at'])
            : new \DateTime();

        return new User(
            new Email($row['email']),
            $row['password_hash'],
            (int)$row['id'],
            $createdAt
        );
    }
}

// Esse arquivo possui código gerado em colaboração com IA

