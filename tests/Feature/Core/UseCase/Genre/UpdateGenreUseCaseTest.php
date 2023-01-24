<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Category as ModelCategory;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\DTO\Genre\Update\GenreUpdateInputDto;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{
    public function test_update(): void
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new UpdateGenreUseCase($repository, $repositoryCategory, new DBTransaction());

        $genre = Model::factory()->create();

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $useCase->execute(new GenreUpdateInputDto(
            id: $genre->id,
            name: 'Name Updated',
            categoriesId: $categoriesIds
        ));

        $this->assertDatabaseHas('genres', [
            'name' => 'Name Updated'
        ]);

        $this->assertDatabaseCount('category_genre', 10);
    }


    public function test_update_exception(): void
    {
        $fakeId = 'fake_id';
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Category $fakeId not found");

        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new UpdateGenreUseCase($repository, $repositoryCategory, new DBTransaction());

        $genre = Model::factory()->create();

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds,$fakeId);

        $useCase->execute(new GenreUpdateInputDto(
            id: $genre->id,
            name: 'test',
            categoriesId: $categoriesIds
        ));
    }


    public function test_update_with_transaction(): void
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new UpdateGenreUseCase($repository, $repositoryCategory, new DBTransaction());

        $genre = Model::factory()->create();

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $useCase->execute(new GenreUpdateInputDto(
                id: $genre->id,
                name: 'test',
                categoriesId: $categoriesIds
            ));

            $this->assertDatabaseHas('genres', [
                'name' => 'test'
            ]);

            $this->assertDatabaseCount('category_genre', 10);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }
    }
}
