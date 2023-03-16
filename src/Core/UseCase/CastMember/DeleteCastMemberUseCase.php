<?php


namespace Core\UseCase\CastMember;


use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Delete\{DeleteCastMemberInputDto, DeleteCastMemberOutputDto};

class DeleteCastMemberUseCase
{

    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteCastMemberInputDto $input)
    {
        $isDeleted = $this->repository->delete($input->id);

        return new DeleteCastMemberOutputDto(
            success: $isDeleted,
        );
    }

}
