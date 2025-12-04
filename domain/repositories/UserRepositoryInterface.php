<?php

namespace Domain\repositories;

use Domain\entities\User;
use Domain\value_objects\Email;

/**
 * Interface para repositório de usuários
 */
interface UserRepositoryInterface
{
    public function findByEmail(Email $email): ?User;
    public function findById(int $id): ?User;
    public function save(User $user): User;
    public function emailExists(Email $email): bool;
}

// Esse arquivo possui código gerado em colaboração com IA

