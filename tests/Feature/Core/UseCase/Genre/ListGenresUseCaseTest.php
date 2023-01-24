<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Tests\TestCase;

class ListGenresUseCaseTest extends TestCase
{
    public function test_list_all()
    {
        $repository = new GenreEloquentRepository(new Model());
        $useCase = new ListGenresUseCase($repository);

        Model::factory()->count(100)->create();

        $response = $useCase->execute(new ListGenresInputDto());

        $this->assertCount(15, $response->items);
        $this->assertEquals(100, $response->total);

    }
}
