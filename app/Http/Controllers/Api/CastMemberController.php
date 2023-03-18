<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CastMemberResource;
use Core\UseCase\DTO\CastMember\CastMemberInputDto;
use Core\UseCase\DTO\CastMember\Create\CastMemberCreateInputDto;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberInputDto;
use Core\UseCase\DTO\CastMember\List\ListCastMembersInputDto;
use Core\UseCase\DTO\CastMember\Update\UpdateCastMemberInputDto;
use App\Http\Requests\{StoreCastMemberRequest, UpdateCastMemberRequest};
use Core\UseCase\CastMember\{
    CreateCastMemberUseCase,
    DeleteCastMemberUseCase,
    ListCastMembersUseCase,
    ListCastMemberUseCase,
    UpdateCastMemberUseCase
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    public function index(Request $request, ListCastMembersUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCastMembersInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int)$request->get('page', 1),
                totalPage: (int)$request->get('totalPage', 15),
            )
        );


        return CastMemberResource::collection(collect($response->items))->additional([
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

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CastMemberCreateInputDto(
                name: $request->name,
                type: (int)$request->type
            )
        );


        return (new CastMemberResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(ListCastMemberUseCase $useCase, $id)
    {
        $cast_member = $useCase->execute(new CastMemberInputDto($id));

        return (new CastMemberResource($cast_member))
            ->response();
    }

    public function update(UpdateCastMemberRequest $request, UpdateCastMemberUseCase $useCase, $id)
    {
        $cast_member = $useCase->execute(new UpdateCastMemberInputDto(
            id: $id,
            name: $request->name
        ));

        return (new CastMemberResource($cast_member))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCastMemberUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteCastMemberInputDto(id: $id));
        return response()->noContent();
    }

}
