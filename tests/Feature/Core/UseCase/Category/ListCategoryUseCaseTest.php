<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryListInputDto;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list()
    {
        $categoryModel = Model::factory()->create();
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new ListCategoryUseCase($repository);

        $response = $useCase->execute(new CategoryListInputDto(
            id: $categoryModel->id,
        ));
        $this->assertEquals($categoryModel->name, $response->name);
        $this->assertEquals($categoryModel->id, $response->id);
        $this->assertEquals($categoryModel->description, $response->description);
    }
}
