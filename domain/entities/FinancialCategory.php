<?php

namespace Domain\entities;

/**
 * Entidade FinancialCategory
 * 
 * Representa uma categoria financeira (ex: Salário, Alimentação, etc).
 */
class FinancialCategory
{
    private ?int $id;
    private string $name;
    private ?string $description;
    private int $financialTypeId;

    public function __construct(
        string $name,
        int $financialTypeId,
        ?string $description = null,
        ?int $id = null
    ) {
        $this->name = trim($name);
        $this->financialTypeId = $financialTypeId;
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

    public function getFinancialTypeId(): int
    {
        return $this->financialTypeId;
    }
}

// Esse arquivo possui código gerado em colaboração com IA

