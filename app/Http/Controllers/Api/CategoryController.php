<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\DTO\Category\ListCategories\CategoriesListInputDto;
use Core\UseCase\Category\{
    ListCategoriesUseCase
};
use Illuminate\Http\Request;

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
}
