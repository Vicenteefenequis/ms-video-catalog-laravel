<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\UseCase\DTO\CastMember\Create\{
    CastMemberCreateInputDto,
    CastMemberCreateOutputDto
};
use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        $mockEntity = Mockery::mock(CastMember::class,['name',CastMemberType::ACTOR]);
        $mockEntity->shouldReceive('id');
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->once()->andReturn($mockEntity);
        $useCase = new CreateCastMemberUseCase($mockRepository);

        $mockDto = Mockery::mock(CastMemberCreateInputDto::class, [
            'name', 1
        ]);
        $response = $useCase->execute($mockDto);

        $this->assertInstanceOf(CastMemberCreateOutputDto::class,$response);
        $this->assertNotEmpty($response->id);
        $this->assertEquals('name',$response->name);
        $this->assertEquals(1,$response->type);
        $this->assertNotEmpty($response->createdAt);

        Mockery::close();
    }
}
