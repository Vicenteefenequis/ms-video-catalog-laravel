<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Core\UseCase\DTO\Category\DeleteCategory\DeleteCategoryInputDto;
use Core\UseCase\DTO\Category\UpdateCategory\UpdateCategoryInputDto;
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use App\Http\Resources\CategoryResource;
use Core\UseCase\DTO\Category\CategoryListInputDto;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\ListCategories\CategoriesListInputDto;
use Core\UseCase\Category\{CreateCategoryUseCase,
    DeleteCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CategoriesListInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int)$request->get('page', 15),
                totalPage: (int)$request->get('totalPage', 1),
            )
        );


        return CategoryResource::collection(collect($response->items))->additional([
            'meta' => [
                'total' => $response->total,
                'current_page' => $response->current_page,
                'last_page' => $response->last_page,
                'first_page' => $response->first_page,
                'per_page' => $response->per_page,
                'to' => $response->to,
                'from' => $response->from
            ]
        ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CategoryCreateInputDto(
                name: $request->name,
                description: $request->description ?? "",
                isActive: (bool)$request->is_active ?? true,
            )
        );


        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(ListCategoryUseCase $useCase, $id)
    {
        $category = $useCase->execute(new CategoryListInputDto($id));

        return (new CategoryResource($category))
            ->response();
    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase, $id)
    {
        $category = $useCase->execute(new UpdateCategoryInputDto(
            id: $id,
            name: $request->name
        ));

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCategoryUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteCategoryInputDto(id: $id));
        return response()->noContent();
    }

}
