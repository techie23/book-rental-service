<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function search(Request $request){

       /**
     * Search for books by name and/or genre with input validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
 
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $name = trim($request->query('name'));
        $genre = trim($request->query('genre'));

        $query = Book::query();

        if ($name) {
            $query->where('title',$name);
        }

        if ($genre) {
            $query->where('genre',$genre);
        }
        $books = $query->get();
        return response()->json($books);
    }
}
