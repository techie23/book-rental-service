<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

     /**
     * Get the rentals for the book.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
