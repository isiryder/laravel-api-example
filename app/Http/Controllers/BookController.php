<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Models\Author;
use App\Models\Library;
use App\Repositories\AuthorRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\LibraryRepositoryInterface;


class BookController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->books->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $book = $this->storeBookWithRelations($request);

        return response(['book' => $book, 'message' => 'Created Successfully'], Response::HTTP_CREATED);
    }

    private function storeBookWithRelations($request) {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = $this->books->get($id);

        if (!$book) return response(['message' => 'Not found'], Response::HTTP_NOT_FOUND);

        return $book;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->updateBookWithRelations($request, $id);

        return response([], Response::HTTP_NO_CONTENT);
    }

    private function updateBookWithRelations($request, $id) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = $this->books->get($id);
        if (!$book) return response(['message' => 'Not found'], Response::HTTP_NOT_FOUND);

        $this->books->delete($book);

        return response(['message' => 'Deleted Successfully'], Response::HTTP_OK);
    }
}
