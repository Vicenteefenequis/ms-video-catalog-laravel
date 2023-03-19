<?php

namespace Tests\Unit\UseCase\Video;

use Core\UseCase\Video\Interface\VideoEventManagerInterface;
use Core\UseCase\Interface\{
    FileStorageInterface,
    TransactionInterface
};
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\CreateVideoUseCase as UseCase;

use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_constructor()
    {
        $useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager()
        );
        $this->assertTrue(true);
    }

    private function createMockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }

    private function createMockTransaction()
    {
        return Mockery::mock(stdClass::class, TransactionInterface::class);
    }

    private function createMockFileStorage()
    {
        return Mockery::mock(stdClass::class, FileStorageInterface::class);
    }

    private function createMockEventManager()
    {
        return Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
    }
}
