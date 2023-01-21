<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\DeleteCategory\DeleteCategoryInputDto;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete()
    {
        $categoryModel = Model::factory()->create();
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new DeleteCategoryUseCase($repository);

        $response = $useCase->execute(new DeleteCategoryInputDto($categoryModel->id));

        $this->assertTrue($response->success);
        $this->assertSoftDeleted($categoryModel);
    }
}
