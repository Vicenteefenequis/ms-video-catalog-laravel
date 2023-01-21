<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;

use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategories\CategoriesListOutputDto;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class CategoryControllerUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest
            ->shouldReceive('get')
            ->andReturn('test');

        $mockDtoOutput = Mockery::mock(CategoriesListOutputDto::class, [
            [], 1, 1, 1, 1, 1, 1,
        ]);

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')->once()->andReturn($mockDtoOutput);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUseCase);


        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta',$response->additional);


        Mockery::close();
    }
}
