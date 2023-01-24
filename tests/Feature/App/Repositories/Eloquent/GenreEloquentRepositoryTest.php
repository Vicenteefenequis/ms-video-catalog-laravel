<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Genre as Model;
use Core\Domain\Entity\Genre as Entity;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new Model());
    }

    public function test_implements_interface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function test_insert()
    {
        $entity = new Entity(name: 'Genre');
        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->id(), $response->id());
        $this->assertTrue($response->isActive);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'name' => $entity->name,
            'is_active' => $entity->isActive
        ]);
    }

    public function test_insert_deactivate()
    {
        $entity = new Entity(name: 'Genre');
        $entity->deactivate();
        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->id(), $response->id());
        $this->assertFalse($response->isActive);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'name' => $entity->name,
            'is_active' => $entity->isActive
        ]);
    }

    public function test_insert_with_relationships()
    {
        $categories = Category::factory()->count(4)->create();

        $entity = new Entity(name: 'teste');

        foreach ($categories as $category) {
            $entity->addCategory($category->id);
        }

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id()
        ]);


        $this->assertDatabaseCount('categories', count($entity->categoriesId));

    }

    public function test_not_found()
    {
        $genreId = 'fake_id';
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Genre $genreId not found");

        $this->repository->findById($genreId);
    }


    public function test_find_by_id()
    {
        $genre = Genre::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertEquals($genre->id,$response->id());
        $this->assertEquals($genre->name,$response->name);
        $this->assertTrue($response->isActive);
    }

    public function test_find_all()
    {
        $genres = Model::factory()->count(10)->create();

        $genresDb = $this->repository->findAll();

        $this->assertCount(count($genres), $genresDb);
    }

    public function test_find_all_empty()
    {
        $genresDb = $this->repository->findAll();

        $this->assertCount(0, $genresDb);
    }

    public function test_find_all_with_filter()
    {
        Model::factory()->count(10)->create([
            'name' => 'Teste'
        ]);

        Model::factory()->count(10)->create();

        $genresDb = $this->repository->findAll(
            filter: 'Teste'
        );

        $this->assertCount(10, $genresDb);

        $genresDb = $this->repository->findAll();

        $this->assertCount(20, $genresDb);
    }

}
