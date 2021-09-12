<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Models\Author;
use App\Models\Library;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Repositories\LibraryRepository;


class BookController extends Controller
{
    private $authorRepository;
    private $bookRepository;
    private $libraryRepository;

    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository, LibraryRepository $libraryRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->libraryRepository = $libraryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->bookRepository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $book = $this->bookRepository->create($request->all());

        if ($request->has('author')) {
            $author = $this->authorRepository->create($request->author);
            $book->author_id = $author->id;
            $this->bookRepository->save($book);
        }

        if ($request->has('libraries')) {
            foreach ($request->libraries as $libraryData) {
                $this->updateLibraryInBook($book, $libraryData);
            }
        }

        return response(['book' => $book, 'message' => 'Created Successfully'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = $this->bookRepository->get($id);

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
        $book = $this->bookRepository->get($id);

        if (!$book) return response([], Response::HTTP_NO_CONTENT);

        $book->fill($request->all());

        $this->bookRepository->update($book);

        $this->updateAuthorInBook($book, $request->author);

        if ($request->has('libraries')) {
            $this->updateLibraryInBook($book, $request->libraries[0]);
        }

        return response([], Response::HTTP_NO_CONTENT);
    }

    private function updateAuthorInBook($book, $authorData) {

        if (isset($authorData)) {
            $author = null;
            if (isset($authorData['id'])) {
                $author = $this->authorRepository->get($authorData['id']);
                $author->fill($authorData);
                $this->authorRepository->update($author);
            } else {
                $author = $this->authorRepository->create($authorData);
            }

            if ($book->author_id != $author?->id) {
                $book->author_id = $author?->id;
                $this->bookRepository->save($book);
            }
        }
    }

    private function updateLibraryInBook($book, $libraryData) {

        if (isset($libraryData)) {
            $library = null;
            if (isset($libraryData['id'])) {
                $library = $this->libraryRepository->get($libraryData['id']);
                $library?->update($libraryData);
                $library?->save();
            } else {
                $library = $this->libraryRepository->create($libraryData);
                $library->save();
            }
            $book->libraries()->save($library);
            $this->bookRepository->save($book);
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
        $book = $this->bookRepository->get($id);
        if (!$book) return response(['message' => 'Not found'], Response::HTTP_NOT_FOUND);

        $this->bookRepository->delete($book);

        return response(['message' => 'Deleted Successfully'], Response::HTTP_OK);
    }
}
