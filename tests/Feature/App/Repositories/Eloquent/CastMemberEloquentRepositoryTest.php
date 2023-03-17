<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CastMemberEloquentRepository(new Model());
    }


    public function test_check_implements_interface_repository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function test_insert()
    {
        $entity = new Entity(
            name: 'teste',
            type: CastMemberType::ACTOR
        );

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
        ]);

        $this->assertEquals($entity->name, $response->name);
    }

    public function test_find_by_id_success()
    {
        $entity = new Entity(
            name: 'teste',
            type: CastMemberType::ACTOR
        );

        $this->repository->insert($entity);

        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
            'name' => $entity->name,
            'type' => $entity->type->value,
        ]);

        $response = $this->repository->findById($entity->id());

        $this->assertEquals($entity->id(), $response->id());
        $this->assertEquals($entity->name, $response->name);
    }

    public function test_find_by_id_not_found()
    {
        $uuid = "any_uuid";
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("CastMember $uuid not found");

        $this->repository->findById($uuid);
    }

    public function test_find_all()
    {
        $castMembers = Model::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(count($castMembers), $response);
    }

    public function test_find_all_empty()
    {
        $response = $this->repository->findAll();
        $this->assertCount(0, $response);
    }

    public function test_pagination()
    {
        Model::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());
    }

    public function test_pagination_two()
    {
        Model::factory()->count(80)->create();

        $response = $this->repository->paginate(
            totalPage: 10
        );

        $this->assertCount(10, $response->items());
        $this->assertEquals(80, $response->total());
    }


    public function test_update_not_found()
    {
        $this->expectException(NotFoundException::class);

        $castMember = new Entity(
            "name 1",
            CastMemberType::DIRECTOR,
        );


        $this->repository->update($castMember);
    }

    /**
     * @throws \Exception
     */
    public function test_update_success()
    {
        $castMember = Model::factory()->create();

        $entity = new Entity(
            name: $castMember->name,
            type: $castMember->type,
            id: new Uuid($castMember->id),
            createdAt: new DateTime($castMember->created_at),
        );

        $entity->update('new name');

        $response = $this->repository->update($entity);

        $this->assertEquals('new name', $response->name);
        $this->assertDatabaseHas('cast_members', [
            'name' => 'new name'
        ]);
    }

    public function test_delete_not_found()
    {
        $id = "any_id";
        $this->expectException(NotFoundException::class);

        $this->repository->delete($id);
    }

    public function test_delete()
    {
        $castMember = Model::factory()->create();

        $hasDeleted = $this->repository->delete($castMember->id);

        $this->assertSoftDeleted('cast_members', [
            "id" => $castMember->id
        ]);
        $this->assertTrue($hasDeleted);
    }
}
