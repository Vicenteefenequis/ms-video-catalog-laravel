<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};
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

        $this->mockEntity = Mockery::mock(Genre::class, [
            'teste',
            new ValueObjectUuid($uuid),
            true,
            []
        ]);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);


        $this->mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $this->mockTransaction->shouldReceive('commit');
        $this->mockTransaction->shouldReceive('rollback');

        $this->mockRepoCategory = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepoCategory->shouldReceive('getIdsListIds')->andReturn([$uuid]);

        $useCase = new CreateGenreUseCase($this->mockRepo, $this->mockRepoCategory, $this->mockTransaction);

        $this->mockInputDto = Mockery::mock(GenreCreateInputDto::class, [
            'name',
            [$uuid],
            true
        ]);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(GenreCreateOutputDto::class, $response);

        Mockery::close();
    }
}
