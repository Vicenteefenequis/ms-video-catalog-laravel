<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\CastMember\Update\UpdateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\Update\UpdateCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use Ramsey\Uuid\Nonstandard\Uuid as RamseyUuid;
use stdClass;

class UpdateCastMemberUseCaseUnitTest extends TestCase
{

    public function test_update()
    {
        $uuid = (string)RamseyUuid::uuid4();

        $name = 'name';

        $mockEntity = Mockery::mock(CastMember::class, [
            $name,
            CastMemberType::ACTOR,
            new Uuid($uuid)
        ]);
        $mockEntity->shouldReceive("id")->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive("update");

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive("findById")->times(1)->with($uuid)->andReturn($mockEntity);
        $mockRepository->shouldReceive("update")->times(1)->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(UpdateCastMemberInputDto::class,[
            $uuid,
            "updated"
        ]);

        $useCase = new UpdateCastMemberUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(UpdateCastMemberOutputDto::class,$response);

        Mockery::close();
    }
}
