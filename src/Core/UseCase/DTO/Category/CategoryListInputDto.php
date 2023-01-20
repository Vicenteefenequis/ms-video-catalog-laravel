<?php

namespace Core\UseCase\DTO\Category;


class CategoryListInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}