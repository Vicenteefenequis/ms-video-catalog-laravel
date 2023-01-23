<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\{
    GenreOutputDto,
    GenreInputDto
};
use Core\UseCase\Genre\ListGenreUseCase;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListGenreUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_list_single()
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
        $this->mockRepo->shouldReceive('findById')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(GenreInputDto::class, [
            $uuid,
        ]);

        $useCase = new ListGenreUseCase($this->mockRepo);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(GenreOutputDto::class, $response);

        Mockery::close();
    }
}
