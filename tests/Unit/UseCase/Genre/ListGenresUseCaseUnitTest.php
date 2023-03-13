<?php

namespace Tests\Unit\UseCase\Genre;

use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\List\ListGenresOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListGenresUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;
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




}
