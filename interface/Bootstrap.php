<?php

namespace Interface;

use Domain\repositories\FinancialCategoryRepositoryInterface;
use Domain\repositories\FinancialOperationRepositoryInterface;
use Domain\repositories\FinancialTypeRepositoryInterface;
use Domain\repositories\UserRepositoryInterface;
use Infrastructure\auth\SessionAuthService;
use Infrastructure\database\DatabaseConnection;
use Infrastructure\persistence\FinancialCategoryRepository;
use Infrastructure\persistence\FinancialOperationRepository;
use Infrastructure\persistence\FinancialTypeRepository;
use Infrastructure\persistence\UserRepository;
use UseCase\auth\LoginUserUseCase;
use UseCase\auth\RegisterUserUseCase;
use UseCase\financial\CalculateFinancialStatementUseCase;
use UseCase\financial\CreateFinancialOperationUseCase;
use UseCase\financial\ListFinancialOperationsUseCase;

/**
 * Classe de bootstrap para inicializar dependências
 */
class Bootstrap
{
    private static ?DatabaseConnection $dbConnection = null;
    private static ?UserRepositoryInterface $userRepository = null;
    private static ?FinancialOperationRepositoryInterface $operationRepository = null;
    private static ?FinancialTypeRepositoryInterface $typeRepository = null;
    private static ?FinancialCategoryRepositoryInterface $categoryRepository = null;
    private static ?SessionAuthService $authService = null;

    public static function getDatabaseConnection(): DatabaseConnection
    {
        if (self::$dbConnection === null) {
            self::$dbConnection = new DatabaseConnection();
        }
        return self::$dbConnection;
    }

    public static function getUserRepository(): UserRepositoryInterface
    {
        if (self::$userRepository === null) {
            self::$userRepository = new UserRepository(self::getDatabaseConnection());
        }
        return self::$userRepository;
    }

    public static function getFinancialOperationRepository(): FinancialOperationRepositoryInterface
    {
        if (self::$operationRepository === null) {
            self::$operationRepository = new FinancialOperationRepository(self::getDatabaseConnection());
        }
        return self::$operationRepository;
    }

    public static function getFinancialTypeRepository(): FinancialTypeRepositoryInterface
    {
        if (self::$typeRepository === null) {
            self::$typeRepository = new FinancialTypeRepository(self::getDatabaseConnection());
        }
        return self::$typeRepository;
    }

    public static function getFinancialCategoryRepository(): FinancialCategoryRepositoryInterface
    {
        if (self::$categoryRepository === null) {
            self::$categoryRepository = new FinancialCategoryRepository(self::getDatabaseConnection());
        }
        return self::$categoryRepository;
    }

    public static function getAuthService(): SessionAuthService
    {
        if (self::$authService === null) {
            self::$authService = new SessionAuthService();
        }
        return self::$authService;
    }

    public static function getLoginUseCase(): LoginUserUseCase
    {
        return new LoginUserUseCase(
            self::getUserRepository(),
            self::getAuthService()
        );
    }

    public static function getRegisterUseCase(): RegisterUserUseCase
    {
        return new RegisterUserUseCase(
            self::getUserRepository()
        );
    }

    public static function getCreateFinancialOperationUseCase(): CreateFinancialOperationUseCase
    {
        return new CreateFinancialOperationUseCase(
            self::getFinancialOperationRepository(),
            self::getFinancialTypeRepository(),
            self::getFinancialCategoryRepository()
        );
    }

    public static function getListFinancialOperationsUseCase(): ListFinancialOperationsUseCase
    {
        return new ListFinancialOperationsUseCase(
            self::getFinancialOperationRepository()
        );
    }

    public static function getCalculateFinancialStatementUseCase(): CalculateFinancialStatementUseCase
    {
        return new CalculateFinancialStatementUseCase(
            self::getFinancialOperationRepository()
        );
    }
}

// Esse arquivo possui código gerado em colaboração com IA

