<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\DTO\CastMember\List\{
    ListCastMembersInputDto,
    ListCastMembersOutputDto
};


use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListCastMembersUseCaseUnitTest extends TestCase
{

    use UseCaseTrait;

    public function test_list()
    {

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->once()->andReturn($this->mockPagination());

        $useCase = new ListCastMembersUseCase($mockRepository);

        $mockInputDto = Mockery::mock(ListCastMembersInputDto::class, [
            'filter', 'desc', 1, 1
        ]);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCastMembersOutputDto::class,$response);

        Mockery::close();
    }
}
