<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};
use Core\Domain\Exception\NotFoundException;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\DTO\Genre\Create\{
    GenreCreateInputDto,
    GenreCreateOutputDto
};
use Core\UseCase\Interface\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateGenreUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {

        $uuid = (string)Uuid::uuid4();
        $useCase = new CreateGenreUseCase($this->mockRepository($uuid), $this->mockCategoryRepository($uuid), $this->mockTransaction());
        $response = $useCase->execute($this->mockInputDto([$uuid]));

        $this->assertInstanceOf(GenreCreateOutputDto::class, $response);

    }

    public function test_create_exceptions()
    {
        $this->expectException(NotFoundException::class);
        $uuid = (string)Uuid::uuid4();

        $useCase = new CreateGenreUseCase($this->mockRepository($uuid,0), $this->mockCategoryRepository($uuid), $this->mockTransaction());

        $response = $useCase->execute($this->mockInputDto([$uuid,'fake_id']));

        $this->assertInstanceOf(GenreCreateOutputDto::class, $response);

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

        return $mockEntity;
    }

    private function mockRepository(string $uuid,int $timesCalled = 1)
    {
        $mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepo->shouldReceive('insert')
            ->times($timesCalled)
            ->andReturn($this->mockEntity($uuid));
        return $mockRepo;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        return $mockTransaction;
    }

    private function mockInputDto(array $categoriesIds)
    {
        return Mockery::mock(GenreCreateInputDto::class, [
            'name',
            $categoriesIds,
            true
        ]);
    }

    public  function mockCategoryRepository(string $uuid)
    {
        $mockRepoCategory = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepoCategory->shouldReceive('getIdsListIds')->once()->andReturn([$uuid]);

        return $mockRepoCategory;
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
