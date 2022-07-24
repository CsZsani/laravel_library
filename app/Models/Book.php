<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

class Book extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'authors',
        'description',
        'released_at',
        'cover_image',
        'cover_image_original_name',
        'pages',
        'language_code',
        'isbn',
        'in_stock'
    ];

    public function borrows(){
        return $this->hasMany(Borrow::class)->withTimestamps();
    }

    public function genres(){
        return $this->belongsToMany(Genre::class)->withTimestamps();
    }

    public function getBorrows(){
        return Borrow::all()->where('book_id', $this->id);
    }

    public function getGenres(){
        return $this->genres;
    }

    public function getActiveBorrows(){
        $accepted = Borrow::all()->where('book_id', $this->id)->where('status','ACCEPTED');
        $returned = Borrow::all()->where('book_id', $this->id)->where('status','RETURNED')->pluck('book_id');
        // $good = $accepted->filter(function ($value, $key) use ($returned){
        //     return $returned->contains($value->id);
        // });
        return $accepted;
        //return $accepted->whereNotIn('book_id', $returned);
    }

    public function getAvailability(){
        $instock = $this->in_stock;
        $borrowed = $this->getActiveBorrows()->count();
        return $instock-$borrowed;
    }

    public function isAvailable(){
        return $this->getAvailability() > 0;
    }

    public function getCover(){
        if($this->cover_image === null){
            return 'https://cdn.dribbble.com/users/201599/screenshots/1545461/book.jpg?compress=1&resize=400x300';
        }else{
            //return Storage::url($this->cover_image);
            return $this->cover_image;
        }
    }

    public function isBorrowedByReader($id){
        $pending = Borrow::all()->where('book_id', $this->id)->where('status','PENDING')->where('reader_id', $id);
        $accepted = Borrow::all()->where('book_id', $this->id)->where('status','ACCEPTED')->where('reader_id', $id);
        //$user = $accepted;
        if(count($accepted) !== 0 || count($pending) !== 0) return true;
        else return false;
    }
}
