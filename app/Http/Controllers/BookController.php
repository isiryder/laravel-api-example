<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Models\Author;
use App\Models\Library;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::with('author', 'libraries')->get();
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
            $library = Library::create($request->libraries[0]);
            $book->libraries()->save($library);
            $book->save();
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

        if ($request->has('author')) {
            $author = Author::find($request->author['id']);
            $author?->update($request->author);
            $author?->save();

            if ($book->author_id != $author->id) {
                $book->author_id = $author->id;
                $book->save();
            }
        }

        if ($request->has('libraries')) {
            $library = Library::find($request->libraries[0]['id']);
            $library?->update($request->libraries[0]);
            $library?->save();
        }

        return response([], Response::HTTP_NO_CONTENT);
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
