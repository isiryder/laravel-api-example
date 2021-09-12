<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Library;

class LibraryRepository extends BaseRepository {

    const RELATIONS = [
    ];

    public function __construct(Library $library, array $relations = []) {
        parent::__construct($library, self::RELATIONS);
    }
}