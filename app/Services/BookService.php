<?php

namespace App\Services;

use App\Repositories\AuthorRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\LibraryRepositoryInterface;

class BookService
{
    private $authors;
    private $books;
    private $libraries;

    public function __construct(AuthorRepositoryInterface $authors, BookRepositoryInterface $books, LibraryRepositoryInterface $libraries)
    {
        $this->books = $books;
        $this->authors = $authors;
        $this->libraries = $libraries;
    }

    public  function all() {
        return $this->books->all();
    }

    public  function get($id) {
        return $this->books->get($id);
    }

    public function delete($book) {
        return $this->books->delete($book);
    }

    public function storeBookWithRelations($request) {
        $book = $this->books->create($request->all());

        if ($request->has('author')) {
            $author = $this->authors->create($request->author);
            $book->author_id = $author->id;
            $this->books->save($book);
        }

        if ($request->has('libraries')) {
            foreach ($request->libraries as $libraryData) {
                $this->updateLibraryInBook($book, $libraryData);
            }
        }

        return $book;
    }

    public function updateBookWithRelations($request, $id) {
        $book = $this->books->get($id);

        if (!$book) return;

        $book->fill($request->all());

        $this->books->update($book);

        $this->updateAuthorInBook($book, $request->author);

        if ($request->has('libraries')) {
            $this->updateLibraryInBook($book, $request->libraries[0]);
        }
    }

    private function updateAuthorInBook($book, $authorData) {

        if (isset($authorData)) {
            $author = null;
            if (isset($authorData['id'])) {
                $author = $this->authors->get($authorData['id']);
                $author->fill($authorData);
                $this->authors->update($author);
            } else {
                $author = $this->authors->create($authorData);
            }

            if ($book->author_id != $author?->id) {
                $book->author_id = $author?->id;
                $this->books->save($book);
            }
        }
    }

    private function updateLibraryInBook($book, $libraryData) {

        if (isset($libraryData)) {
            $library = null;
            if (isset($libraryData['id'])) {
                $library = $this->libraries->get($libraryData['id']);
                $library?->update($libraryData);
                $library?->save();
            } else {
                $library = $this->libraries->create($libraryData);
                $library->save();
            }
            $book->libraries()->save($library);
            $this->books->save($book);
        }
    }
}