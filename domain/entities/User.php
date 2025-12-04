<?php

namespace Domain\entities;

use Domain\value_objects\Email;

/**
 * Entidade User
 * 
 * Representa um usuário do sistema.
 */
class User
{
    private ?int $id;
    private Email $email;
    private string $passwordHash;
    private \DateTime $createdAt;

    public function __construct(
        Email $email,
        string $passwordHash,
        ?int $id = null,
        ?\DateTime $createdAt = null
    ) {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->id = $id;
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }
}

// Esse arquivo possui código gerado em colaboração com IA

