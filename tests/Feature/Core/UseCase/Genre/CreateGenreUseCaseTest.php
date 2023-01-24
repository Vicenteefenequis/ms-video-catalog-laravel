<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Category as ModelCategory;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\UseCase\DTO\Genre\Create\GenreCreateInputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_insert()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new CreateGenreUseCase($repository, $repositoryCategory, new DBTransaction());

        $useCase->execute(new GenreCreateInputDto(
            name: 'test'
        ));

        $this->assertDatabaseHas('genres',[
            'name' => 'test'
        ]);
    }
}
