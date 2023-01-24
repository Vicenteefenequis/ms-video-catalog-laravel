<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\GenreInputDto;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
{

    public function test_find_by_id()
    {
        $repository = new GenreEloquentRepository(new Model());
        $useCase = new ListGenreUseCase($repository);

        $genre = Model::factory()->create();

        $response = $useCase->execute(new GenreInputDto(
            id: $genre->id
        ));

        $this->assertEquals($genre->id,$response->id);
        $this->assertEquals($genre->name,$response->name);
        $this->assertTrue($response->is_active);
    }
}
