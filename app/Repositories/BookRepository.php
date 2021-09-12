<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class BookRepository extends BaseRepository {

    const RELATIONS = [
        'author',
        'libraries'
    ];

    public function __construct(Book $book, array $relations = []) {
        parent::__construct($book, self::RELATIONS);
    }
}