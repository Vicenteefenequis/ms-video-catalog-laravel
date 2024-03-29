<?php

namespace tests\Unit\UseCase\Category;



use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\{
    CategoryCreateInputDto,
    CategoryCreateOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testCreateNewCategory()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName= 'name cat';
        $this->mockEntity = Mockery::mock(Category::class,[
            $uuid,
            $categoryName,
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $this->mockRepo = Mockery::mock(stdClass::class,CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

        $this->mockCreateInputDto = Mockery::mock(CategoryCreateInputDto::class,[
            $categoryName,
        ]);

        $useCase = new CreateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockCreateInputDto);

        $this->assertInstanceOf(CategoryCreateOutputDto::class,$responseUseCase);
        $this->assertEquals($categoryName,$responseUseCase->name);
        $this->assertEquals('',$responseUseCase->description);
        $this->assertNotNull($responseUseCase->id);

        /**
         * Spies
         *
         */

        $this->spy = Mockery::spy(stdClass::class,CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('insert')->andReturn($this->mockEntity);

        $useCase = new CreateCategoryUseCase($this->spy);
        $useCase->execute($this->mockCreateInputDto);
        $this->spy->shouldHaveReceived('insert');

        Mockery::close();
    }

}
