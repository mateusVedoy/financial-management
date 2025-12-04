<?php

namespace Domain\value_objects;

/**
 * Value Object para valores monetários
 * 
 * Garante que valores monetários sejam sempre válidos e positivos.
 */
class Money
{
    private float $amount;

    public function __construct(float $amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Valor monetário não pode ser negativo.");
        }
        
        $this->amount = round($amount, 2);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function format(): string
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    public function add(Money $other): Money
    {
        return new Money($this->amount + $other->amount);
    }

    public function subtract(Money $other): Money
    {
        $result = $this->amount - $other->amount;
        return new Money($result);
    }

    public function isGreaterThan(Money $other): bool
    {
        return $this->amount > $other->amount;
    }

    public function isLessThan(Money $other): bool
    {
        return $this->amount < $other->amount;
    }

    public function equals(Money $other): bool
    {
        return abs($this->amount - $other->amount) < 0.01;
    }

    /**
     * Cria Money a partir de string formatada
     * 
     * @param string $value Valor em string (ex: "1.234,56" ou "1234.56")
     * @return Money
     */
    public static function fromString(string $value): Money
    {
        // Remove R$, espaços e pontos
        $value = str_replace(['R$', ' ', '.'], '', $value);
        // Substitui vírgula por ponto
        $value = str_replace(',', '.', $value);
        $amount = floatval($value);
        
        return new Money($amount);
    }
}

// Esse arquivo possui código gerado em colaboração com IA

