<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'reader_id',
        'book_id',
        'status',
        'request_processed_at',
        'request_managed_by',
        'request_processed_message',
        'deadline',
        'returned_at',
        'return_managed_by'
    ];

    public function book(){
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function reader(){
        return $this->belongsTo(User::class, 'reader_id');
    }

    public function request_managed_by(){
        return $this->belongsTo(User::class, 'request_managed_by');
    }

    public function return_managed_by(){
        return $this->belongsTo(User::class, 'return_managed_by');
    }

    public function getReader(){
        return $this->reader;
    }

    public function getBook(){
        return $this->book;
    }

    public function getRequestManager(){
        return User::find($this->request_managed_by);
    }

    public function getReturnManager(){
        return User::find($this->return_managed_by);
    }

    public function manageBook($user_id){

    }

    public function statusInString(){
        if($this->status === 'PENDING'){
            return 'Függőben';
        }elseif($this->status === 'REJECTED'){
            return 'Elutasítva';
        }elseif($this->status === 'ACCEPTED'){
            return 'Elfogadva';
        }elseif($this->status === 'RETURNED'){
            return 'Visszahozva';
        }
    }

    public function statusInColor(){
        if($this->status === 'PENDING'){
            return "#703800";
        }elseif($this->status === 'REJECTED'){
            return "#c90902";
        }elseif($this->status === 'ACCEPTED'){
            return "#09bd12";
        }elseif($this->status === 'RETURNED'){
            return "#34c0eb";
        }
    }

    public function hasReaderLate(){
        $late = Borrow::where('reader_id', $this->reader->id)->where('status', 'ACCEPTED')->get();
        //->where('deadline', '<', date_create('now'))->get();
        $array = [];
        foreach($late as $l){
            if(strtotime($l->deadline) < date_create('now')->getTimestamp()){
                $array[] = $l;
            }
        }
        $late2 = Borrow::where('reader_id', $this->reader->id)->where('status', 'RETURNED')->where('deadline', '<', 'returned_at')->get();
        $array2 = [];
        foreach($late2 as $l){
            if(strtotime($l->deadline) < strtotime($l->returned_at)){
                $array2[] = $l;
            }
        }
        return count($array) > 0 || count($array2) > 0;
    }

    public function hasAcceptedLate(){
        $late = Borrow::where('reader_id', $this->reader->id)->where('status', 'ACCEPTED')->get();
        $array = [];
        foreach($late as $l){
            if(strtotime($l->deadline) < date_create('now')->getTimestamp()){
                $array[] = $l;
            }
        }
        return count($array) > 0;
    }

    public function hasReturnedLate(){
        $late = Borrow::where('reader_id', $this->reader->id)->where('status', 'RETURNED')->where('deadline', '<', 'returned_at')->get();
        $array = [];
        foreach($late as $l){
            if(strtotime($l->deadline) < strtotime($l->returned_at)){
                $array[] = $l;
            }
        }
        return count($array) > 0;
    }

    public function readerAcceptedLate(){
        $lates = Borrow::where('reader_id', $this->reader->id)->where('status', 'ACCEPTED')->get();
        $array = [];
        foreach($lates as $l){
            if(strtotime($l->deadline) < date_create('now')->getTimestamp()){
                $array[] = $l;
            }
        }
        return $array;
    }

    public function readerReturnedLate(){
        $lates = Borrow::where('reader_id', $this->reader->id)->where('status', 'RETURNED')->where('deadline', '<', 'returned_at')->get();
        $array = [];
        foreach($lates as $l){
            if(strtotime($l->deadline) < strtotime($l->returned_at)){
                $array[] = $l;
            }
        }
        return $array;
    }

    public function readerLate(){
        $lates = Borrow::where('reader_id', $this->reader->id)->where('status', 'ACCEPTED')->get();
        //->where('deadline', '<', date_create('now'))->get();
        $array = [];
        foreach($lates as $l){
            if(strtotime($l->deadline) < date_create('now')->getTimestamp()){
                $array[] = $l;
            }
        }
        $lates2 = Borrow::where('reader_id', $this->reader->id)->where('status', 'RETURNED')->where('deadline', '<', 'returned_at')->get();
        $array2 = [];
        foreach($lates2 as $l){
            if(strtotime($l->deadline) < strtotime($l->returned_at)){
                $array2[] = $l;
            }
        }
        //return $array2;
        return array_merge($array,$array2);
    }

    public static function lateBorrow($id){
        $borrow = Borrow::find($id);
        if($borrow->deadline && $borrow->status === 'ACCEPTED'&& strtotime($borrow->deadline) < date_create('now')->getTimestamp()) return true;
        else return false;
    }

    public static function allBorrowOfUser($id){
        $count = count(Borrow::where('reader_id', $id)->get());
        return $count;
    }

    public static function booksAtReader($id){
        $books = Borrow::where('reader_id', $id)->where('status', 'ACCEPTED')->get();
        return count($books);
    }

    public static function lateBooksAtReader($id){
        $lates = Borrow::where('reader_id', $id)->where('status', 'ACCEPTED')->get();
        $array = [];
        foreach($lates as $l){
            if(strtotime($l->deadline) < date_create('now')->getTimestamp()){
                $array[] = $l;
            }
        }
        return count($array);
    }
}
