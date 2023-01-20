<?php

namespace tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Core\UseCase\DTO\Category\UpdateCategory\{
    UpdateCategoryInputDto,
    UpdateCategoryOutputDto
};
use stdClass;

class UpdateCategoryUseCaseUnitTest extends TestCase
{

    public function testRenameCategory()
    {

        $id = (string)Uuid::uuid4()->toString();
        $categoryName = 'any_category';
        $categoryDesc = 'Desc';

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            $categoryName,
            $categoryDesc
        ]);
        $this->mockEntity->shouldReceive('update');
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepo->shouldReceive('update')->andReturn($this->mockEntity);


        $this->mockInputDto = Mockery::mock(UpdateCategoryInputDto::class, [
            $id,
            'new name',
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepo);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(UpdateCategoryOutputDto::class, $response);

        /*
         * Spies
         * */


        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->spy->shouldReceive('update')->andReturn($this->mockEntity);
        $useCase = new UpdateCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('findById');
        $this->spy->shouldHaveReceived('update');

    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}


