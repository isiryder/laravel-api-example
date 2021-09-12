<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Author;

class AuthorRepository extends BaseRepository {

    const RELATIONS = [
    ];

    public function __construct(Author $author, array $relations = []) {
        parent::__construct($author, self::RELATIONS);
    }
}