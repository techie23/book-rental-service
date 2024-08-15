<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validatedData['book_id']);

        if ($book->available_copies < 1) {
            return response()->json(['error' => 'No copies available for rent'], 400);
        }

        $rental = new Rental();
        $rental->user_id = auth()->id(); 
        $rental->book_id = $validatedData['book_id'];
        $rental->rental_date = Carbon::now();
        $rental->due_date = Carbon::now()->addWeeks(2);
        $rental->is_overdue = false;
        $rental->save();

        $book->available_copies -= 1;
        $book->save();

        return response()->json(['message' => 'Book rented successfully', 'rental' => $rental], 200);
    }

    public function returnBook(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user_id = auth()->id();

        $rental = Rental::where('user_id', $user_id)
                        ->where('book_id', $validatedData['book_id'])
                        ->whereNull('return_date') 
                        ->first();

        if (!$rental) {
            return response()->json(['error' => 'Rental record not found or book already returned.'], 400);
        }

        $rental->return_date = Carbon::now();
        $rental->is_overdue = false; // need to check this logic later - TO DO
        $rental->save();

        $book = Book::find($validatedData['book_id']);
        $book->available_copies += 1;
        $book->save();

        return response()->json(['message' => 'Book returned successfully.']);
    }

    public function rentalHistory(Request $request){
        $user_id = auth()->id();
        $perPage = 10;

        if(!empty($request->per_page)){
            $perPage = $request->per_page;
        }
        $history = Rental::where('user_id',$user_id)->paginate($perPage);

        if($history->isEmpty()){
            return response()->json(['data' => "No records found"], 200); 
        }
        return response()->json($history, 200);

    }

    public function rentalStats(){
        // Stats for most Overdue book
        $mostOverdueBook = Rental::where('is_overdue', 1)
                            ->select('book_id', DB::raw('COUNT(*) as count'))
                            ->groupBy('book_id')
                            ->orderBy('count', 'desc')
                            ->with('book') 
                            ->first();
        // Stats for Most popular book 
        $mostPopularBook = Rental::select('book_id', DB::raw('COUNT(*) as count'))
                            ->groupBy('book_id')
                            ->orderBy('count', 'desc')
                            ->with('book') 
                            ->first(); 

        // Stats for Least popular book
        $leastPopularBook = Rental::select('book_id', DB::raw('COUNT(*) as count'))
                            ->groupBy('book_id')
                            ->orderBy('count', 'asc')
                            ->with('book') 
                            ->first(); 

        return response()->json([
            'most_overdue_book' => $mostOverdueBook ?? 0,
            'most_popular_books' => $mostPopularBook ?? 0,
            'least_popular_books' => $leastPopularBook ?? 0,
        ], 200);

    }
}

