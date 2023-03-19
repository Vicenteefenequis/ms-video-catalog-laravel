<?php

namespace Core\UseCase\Interface;

interface FileStorageInterface
{
    /**
     * @param string $path
     * @param array $FILES[file]
     * @return string
     */
    public function store(string $path,array $file): string;

    public function delete(string $path): void;
}
