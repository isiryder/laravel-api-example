<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BookRepositoryInterface;
use App\Models\Book;

class BookRepository extends BaseRepository implements BookRepositoryInterface {

    const RELATIONS = [
        'author',
        'libraries'
    ];

    public function __construct(Book $book, array $relations = []) {
        parent::__construct($book, self::RELATIONS);
    }
}