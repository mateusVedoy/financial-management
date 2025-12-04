<?php

namespace Domain\entities;

use Domain\value_objects\Money;

/**
 * Entidade FinancialOperation
 * 
 * Representa uma operação financeira (receita ou despesa).
 */
class FinancialOperation
{
    private ?int $id;
    private Money $value;
    private \DateTime $moment;
    private int $userId;
    private int $financialCategoryId;
    private int $financialTypeId;

    public function __construct(
        Money $value,
        int $userId,
        int $financialCategoryId,
        int $financialTypeId,
        ?\DateTime $moment = null,
        ?int $id = null
    ) {
        $this->value = $value;
        $this->userId = $userId;
        $this->financialCategoryId = $financialCategoryId;
        $this->financialTypeId = $financialTypeId;
        $this->moment = $moment ?? new \DateTime();
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): Money
    {
        return $this->value;
    }

    public function getMoment(): \DateTime
    {
        return $this->moment;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getFinancialCategoryId(): int
    {
        return $this->financialCategoryId;
    }

    public function getFinancialTypeId(): int
    {
        return $this->financialTypeId;
    }
}

// Esse arquivo possui código gerado em colaboração com IA

