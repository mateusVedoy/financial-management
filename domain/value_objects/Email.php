<?php

namespace Domain\value_objects;

/**
 * Value Object para Email
 * 
 * Garante que um email sempre seja válido.
 */
class Email
{
    private string $value;

    public function __construct(string $email)
    {
        $email = trim($email);
        
        if (empty($email)) {
            throw new \InvalidArgumentException("Email não pode ser vazio.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email inválido: {$email}");
        }
        
        $this->value = strtolower($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
}

// Esse arquivo possui código gerado em colaboração com IA

