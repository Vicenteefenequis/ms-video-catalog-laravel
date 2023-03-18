<?php


namespace Core\UseCase\CastMember;


use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Update\{
    UpdateCastMemberInputDto,
    UpdateCastMemberOutputDto
};

class UpdateCastMemberUseCase
{

    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateCastMemberInputDto $input): UpdateCastMemberOutputDto
    {
        $castMember = $this->repository->findById($input->id);

        $castMember->update($input->name);

        $updatedCastMember = $this->repository->update($castMember);

        return new UpdateCastMemberOutputDto(
            id: $updatedCastMember->id(),
            name: $updatedCastMember->name,
            type: $updatedCastMember->type->value,
            created_at: $updatedCastMember->createdAt()
        );
    }

}
