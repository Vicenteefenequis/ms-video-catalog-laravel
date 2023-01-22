<?php


namespace tests\Unit\UseCase\Category;


use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\ListCategoriesUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Core\UseCase\DTO\Category\ListCategories\{
    CategoriesListInputDto,
    CategoriesListOutputDto
};
use stdClass;

class ListCategoriesUseCaseUnitTest extends TestCase
{
    public function testListCategoriesEmpty()
    {
        $mockPagination = $this->mockPagination();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')->once()->andReturn($mockPagination);

        $this->mockInputDto = Mockery::mock(CategoriesListInputDto::class, ['filter', 'desc']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $response = $useCase->execute($this->mockInputDto);


        $this->assertInstanceOf(CategoriesListOutputDto::class, $response);
        $this->assertCount(0, $response->items);
    }




    public function testListCategories()
    {
        $register = new stdClass();
        $register->id = 'any_id';
        $register->name = 'name';
        $register->description = 'description';
        $register->is_active = 'is_active';
        $register->created_at = 'created_at';
        $register->updated_at = 'updated_at';
        $register->deleted_at = 'deleted_at';


        $mockPagination = $this->mockPagination([
            $register
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')->andReturn($mockPagination);

        $this->mockInputDto = Mockery::mock(CategoriesListInputDto::class, ['filter', 'desc']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertCount(1, $response->items);
        $this->assertInstanceOf(CategoriesListOutputDto::class, $response);
        $this->assertInstanceOf(stdClass::class,$response->items[0]);

    }


    protected function mockPagination(array $items = [])
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(0);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockPagination->shouldReceive('to')->andReturn(0);
        $this->mockPagination->shouldReceive('from')->andReturn(0);

        return $this->mockPagination;
    }


    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
