<?php

namespace App\Services\DTO;

class GetTaskDto
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
        public ?array $filters = null,
        public ?array $sort = null,
        public ?string $search = null,
    ){
    }
}
