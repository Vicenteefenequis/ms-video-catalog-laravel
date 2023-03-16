<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberInputDto;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Ramsey\Uuid\Nonstandard\Uuid as RamseyUuid;
use stdClass;

class DeleteCastMemberUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_delete()
    {
        $uuid = (string)RamseyUuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive("delete")->times(1)->with($uuid)->andReturn(true);

        $mockInputDto = Mockery::mock(DeleteCastMemberInputDto::class, [$uuid]);

        $useCase = new DeleteCastMemberUseCase($mockRepository);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(DeleteCastMemberOutputDto::class,$response);
        $this->assertTrue($response->success);

        Mockery::close();
    }
}
