<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AuthorRepositoryInterface;
use App\Models\Author;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface {

    const RELATIONS = [
    ];

    public function __construct(Author $author, array $relations = []) {
        parent::__construct($author, self::RELATIONS);
    }
}