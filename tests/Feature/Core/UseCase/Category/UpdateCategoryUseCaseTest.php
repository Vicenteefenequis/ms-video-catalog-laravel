<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\UpdateCategory\UpdateCategoryInputDto;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update()
    {
        $categoryModel = Model::factory()->create();
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new UpdateCategoryUseCase($repository);

        $response = $useCase->execute(new UpdateCategoryInputDto(id: $categoryModel->id, name: 'Name updated'));

        $this->assertEquals('Name updated', $response->name);
        $this->assertEquals($categoryModel->description, $response->description);
        $this->assertDatabaseHas('categories',[
            'name' => $response->name,
        ]);
    }
}
