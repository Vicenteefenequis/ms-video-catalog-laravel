<?php

namespace Core\UseCase\Video\Create;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    VideoRepositoryInterface,
    GenreRepositoryInterface
};
use Core\Domain\ValueObject\Media;
use Core\UseCase\Interface\{
    EventManagerInterface,
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Throwable;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface      $repository,
        protected TransactionInterface          $transaction,
        protected FileStorageInterface          $storage,
        protected EventManagerInterface         $eventManager,
        protected CategoryRepositoryInterface   $repositoryCategory,
        protected GenreRepositoryInterface      $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
    )
    {
    }


    public function execute(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {

        $entity = $this->createEntity($input);

        $this->repository->insert($entity);

        try {
            $this->repository->insert($entity);

            if ($pathMedia = $this->storeMedia($entity->id(), $input->videoFile)) {
                $media = new Media(filePath: $pathMedia, mediaStatus: MediaStatus::PROCESSING);
                $entity->setVideoFile($media);
                $this->repository->updateMedia($entity);
                $this->eventManager->dispatch(new VideoCreatedEvent($entity));
            };
            // $eventManager
            $this->transaction->commit();

            return $this->output($entity);

        } catch (Throwable $th) {
            $this->transaction->rollback();
            //if (isset($pathMedia)) $this->storage->delete($pathMedia);
            throw $th;
        }


    }

    private function createEntity(CreateInputVideoDTO $input): Entity
    {
        $entity = new Entity(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating
        );

        $this->validateCategoriesId($input->categories);
        foreach ($input->categories as $category) {
            $entity->addCategoryId($category);
        }

        $this->validateGenresId($input->genres);
        foreach ($input->genres as $genre) {
            $entity->addGenreId($genre);
        }

        $this->validateCastMembersId($input->castMembers);
        foreach ($input->castMembers as $castMember) {
            $entity->addCastMemberId($castMember);
        }


        return $entity;
    }

    private function storeMedia(string $path, ?array $media = null): string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media
            );
        }

        return '';
    }

    private function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->repositoryCategory->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateGenresId(array $genresId = [])
    {
        $genresDb = $this->repositoryGenre->getIdsListIds($genresId);

        $arrayDiff = array_diff($genresId, $genresDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Genres' : 'Genre',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateCastMembersId(array $castMembersId = [])
    {
        $castMemberDb = $this->repositoryCastMember->getIdsListIds($castMembersId);

        $arrayDiff = array_diff($castMembersId, $castMemberDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'CastMembers' : 'CastMember',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function output(Entity $entity): CreateOutputVideoDTO
    {
        return new CreateOutputVideoDTO(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating->value,
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMembersId,
            videoFile: $entity->videoFile()?->filePath,
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->getPath(),
            thumbHalf: $entity->thumbHalf()?->getPath(),
            bannerFile: $entity->bannerFile()?->getPath(),
        );
    }
}
