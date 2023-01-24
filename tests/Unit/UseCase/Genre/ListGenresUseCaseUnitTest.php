<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\List\ListGenresOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_usecase()
    {
        $this->mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->once()->andReturn($this->mockPagination());

        $this->mockDtoInput = Mockery::mock(ListGenresInputDto::class, [
            'teste', 'desc', 1, 15
        ]);

        $useCase = new ListGenresUseCase($this->mockRepository);

        $reponse = $useCase->execute($this->mockDtoInput);
        $this->assertInstanceOf(ListGenresOutputDto::class,$reponse);

        Mockery::close();


    }



    protected function mockPagination(array $items = [])
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(0);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockPagination->shouldReceive('to')->andReturn(0);
        $this->mockPagination->shouldReceive('from')->andReturn(0);

        return $this->mockPagination;
    }
}
