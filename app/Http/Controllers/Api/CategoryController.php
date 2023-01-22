<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\ListCategories\CategoriesListInputDto;
use Core\UseCase\Category\{CreateCategoryUseCase, ListCategoriesUseCase};
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
                isActive: (bool) $request->is_active ?? true,
            )
        );

        return (new CategoryResource(collect($response)))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }
}