<?php

namespace Domain\entities;

/**
 * Entidade FinancialType
 * 
 * Representa um tipo financeiro (Receita ou Despesa).
 */
class FinancialType
{
    private ?int $id;
    private string $name;
    private ?string $description;

    public function __construct(
        string $name,
        ?string $description = null,
        ?int $id = null
    ) {
        $this->name = trim($name);
        $this->description = $description ? trim($description) : null;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isIncome(): bool
    {
        return strtolower($this->name) === 'receita';
    }

    public function isExpense(): bool
    {
        return strtolower($this->name) === 'despesa';
    }
}

// Esse arquivo possui código gerado em colaboração com IA

