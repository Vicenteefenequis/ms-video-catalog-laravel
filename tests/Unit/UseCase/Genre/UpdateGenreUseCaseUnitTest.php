<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\Update\{
    GenreUpdateOutputDto,
    GenreUpdateInputDto
};
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\UseCase\Interface\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateGenreUseCaseUnitTest extends TestCase
{
    public function test_update()
    {

        $uuid = (string)Uuid::uuid4();
        $useCase = new UpdateGenreUseCase($this->mockRepository($uuid), $this->mockCategoryRepository($uuid), $this->mockTransaction());
        $response = $useCase->execute($this->mockInputDto($uuid,[$uuid]));

        $this->assertInstanceOf(GenreUpdateOutputDto::class, $response);

    }

    public function test_update_exceptions()
    {
        $this->expectException(NotFoundException::class);
        $uuid = (string)Uuid::uuid4();

        $useCase = new UpdateGenreUseCase($this->mockRepository($uuid), $this->mockCategoryRepository($uuid), $this->mockTransaction());

        $response = $useCase->execute($this->mockInputDto($uuid, [$uuid, 'fake_id']));

        $this->assertInstanceOf(GenreUpdateOutputDto::class, $response);

    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = Mockery::mock(Genre::class, [
            'teste',
            new ValueObjectUuid($uuid),
            true,
            []
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update');
        $mockEntity->shouldReceive('addCategory');

        return $mockEntity;
    }

    private function mockRepository(string $uuid)
    {
        $mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepo->shouldReceive('findById')->andReturn($this->mockEntity($uuid));
        $mockRepo->shouldReceive('update')->andReturn($this->mockEntity($uuid));
        return $mockRepo;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        return $mockTransaction;
    }

    private function mockInputDto(string $uuid, array $categoriesIds)
    {
        return Mockery::mock(GenreUpdateInputDto::class, [
            $uuid,
            'name to update',
            $categoriesIds,

        ]);
    }

    public function mockCategoryRepository(string $uuid)
    {
        $mockRepoCategory = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepoCategory->shouldReceive('getIdsListIds')->andReturn([$uuid]);

        return $mockRepoCategory;
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
