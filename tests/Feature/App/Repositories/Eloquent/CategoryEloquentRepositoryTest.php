<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as Model;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Tests\TestCase;
use Throwable;

class CategoryEloquentRepositoryTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new Model());
    }

    public function testInsertCategory()
    {

        $entity = new EntityCategory(
            name: 'Teste'
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name,
        ]);
    }


    public function testFindById()
    {
        $category = Model::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function testFindNotFound()
    {
        try {
            $this->repository->findById('fakeValue');
            $this->fail();
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }


    public function testFindAll()
    {
        Model::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function testPaginate()
    {
        Model::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);

        $this->assertCount(15, $response->items());
    }

    public function testPaginateWithoutData()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);

        $this->assertCount(0, $response->items());
    }


    public function testUpdateIdNotFound()
    {

        try {
            $category = new EntityCategory(name: 'test');
            $this->repository->update($category);
            $this->fail();
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }


    public function testUpdate()
    {

        $categoryDb = Model::factory()->create();

        $category = new EntityCategory(
            id: $categoryDb->id,
            name: 'updated name',
        );
        $response = $this->repository->update($category);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertNotEquals($response->name, $categoryDb->name);
        $this->assertEquals('updated name', $response->name);
    }


    public function testDeleteIdNotFound()
    {

        try {
            $this->repository->delete('fake_id');
            $this->fail();
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th, 'Category Not Found');
        }
    }


    public function testDelete()
    {
        $categoryDb = Model::factory()->create();
        $response = $this->repository->delete($categoryDb->id);
        $this->assertTrue($response);
    }

}
