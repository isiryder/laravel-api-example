<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Models\Author;
use App\Models\Library;
use App\Repositories\BookRepository;


class BookController extends Controller
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo Form::open(array('url' => 'foo/bar', 'method' => 'put'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookInput = $request->only((new Book)->getFillable());
        $book = Book::create($bookInput);

        if ($request->has('author')) {
            $author = Author::create($request->author);
            $book->author_id = $author->id;
            $book->save();
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
        return Book::with('author', 'libraries')->findOrFail($id);
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
        $book = Book::find($id);

        $bookInput = $request->only((new Book)->getFillable());

        $book?->update($bookInput);

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
                $author = Author::find($authorData['id']);
                $author?->update($authorData);
                $author?->save();
            } else {
                $author = Author::create($authorData);
            }

            if ($book->author_id != $author?->id) {
                $book->author_id = $author?->id;
                $book->save();
            }
        }
    }

    private function updateLibraryInBook($book, $libraryData) {

        if (isset($libraryData)) {
            $library = null;
            if (isset($libraryData['id'])) {
                $library = Library::find($libraryData['id']);
                $library?->update($libraryData);
                $library?->save();
            } else {
                $library = Library::create($libraryData);
                $library->save();
            }
            $book->libraries()->save($library);
            $book->save();
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
        $book = Book::findOrFail($id);
        $book->delete();

        return response(['message' => 'Deleted Successfully'], Response::HTTP_OK);
    }
}
