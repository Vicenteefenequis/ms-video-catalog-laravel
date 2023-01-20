<?php


namespace tests\Unit\UseCase\Category;


use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryListInputDto;
use Core\UseCase\DTO\Category\CategoryListOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $uuid = (string)Uuid::uuid4()->toString();
        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            'test_category',
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));


        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
            ->with($uuid)
            ->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryListInputDto::class,[
            $uuid,
        ]);

        $usecase = new ListCategoryUseCase($this->mockRepo);
        $response = $usecase->execute($this->mockInputDto);


        $this->assertInstanceOf(CategoryListOutputDto::class,$response);
        $this->assertEquals('test_category',$response->name);
        $this->assertEquals($uuid,$response->id);


        /**
         * Spies
         *
         */


        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')
            ->with($uuid)
            ->andReturn($this->mockEntity);

        $usecase = new ListCategoryUseCase($this->spy);
        $usecase->execute($this->mockInputDto);
        $this->spy->shouldHaveReceived('findById');
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}