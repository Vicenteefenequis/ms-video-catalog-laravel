<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interface\EventManagerInterface;
use Core\UseCase\Interface\FileStorageInterface;
use Core\UseCase\Interface\TransactionInterface;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface     $transaction,
        protected FileStorageInterface     $storage,
        protected EventManagerInterface    $eventManager,
    ) { }
}
