<?php


namespace tests\Unit\UseCase\Category;


use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\UseCase\DTO\Category\DeleteCategory\{
    DeleteCategoryInputDto,
    DeleteCategoryOutputDto,
};
use stdClass;


class DeleteCategoryUseCaseUnitTest extends TestCase
{

    public function testDeleteCategory()
    {

        $this->mockRepo = Mockery::mock(stdClass::class,CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(true);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);

        $this->mockInputDto = Mockery::mock(DeleteCategoryInputDto::class,[
            'any_id'
        ]);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(DeleteCategoryOutputDto::class,$response);
        $this->assertTrue($response->success);

        /*
         * Spies
         * */

        $this->spy = Mockery::spy(stdClass::class,CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);

        $useCase = new DeleteCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('delete');

    }


    public function testDeleteCategoryFalse()
    {

        $this->mockRepo = Mockery::mock(stdClass::class,CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(false);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);

        $this->mockInputDto = Mockery::mock(DeleteCategoryInputDto::class,[
            'any_id'
        ]);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(DeleteCategoryOutputDto::class,$response);
        $this->assertFalse($response->success);
    }


    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}


