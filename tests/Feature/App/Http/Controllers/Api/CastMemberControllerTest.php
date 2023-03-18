<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\UseCase\CastMember\{
    CreateCastMemberUseCase,
    ListCastMemberUseCase,
    ListCastMembersUseCase,
    UpdateCastMemberUseCase,
    DeleteCastMemberUseCase
};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CastMemberControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CastMemberEloquentRepository(new Model());
        $this->controller = new CastMemberController();
        parent::setUp();
    }

    public function test_index()
    {
        $useCase = new ListCastMembersUseCase($this->repository);

        $response = $this->controller->index(new Request(), $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_store()
    {
        $useCase = new CreateCastMemberUseCase($this->repository);

        $request = new StoreCastMemberRequest();

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Test',
            'type' => 1
        ]));

        $response = $this->controller->store($request, $useCase);


        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $cast_member = Model::factory()->create();
        $response = $this->controller->show(useCase: new ListCastMemberUseCase($this->repository), id: $cast_member->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());

    }

    public function test_update()
    {
        $cast_member = Model::factory()->create();
        $useCase = new UpdateCastMemberUseCase($this->repository);

        $request = new UpdateCastMemberRequest();

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Updated',
            'type' => 2
        ]));

        $response = $this->controller->update(
            request: $request,
            useCase: $useCase,
            id: $cast_member->id,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertDatabaseHas('cast_members', [
            'name' => 'Updated'
        ]);
    }


    public function test_delete()
    {
        $cast_member = Model::factory()->create();
        $response = $this->controller->destroy(
            useCase: new DeleteCastMemberUseCase($this->repository),
            id: $cast_member->id
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT,$response->status());

    }
}
