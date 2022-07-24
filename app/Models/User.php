<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reader(){
        return $this->hasMany(Borrow::class)->withTimestamps();
    }

    public function request_manager(){
        return $this->hasMany(Borrow::class)->withTimestamps();
    }

    public function return_manager(){
        return $this->hasMany(Borrow::class)->withTimestamps();
    }

    public function isLibrarian(){
        if($this->is_librarian === '1'){
            return true;
        }else{
            return false;
        }
    }

    public function isReader(){
        if($this->is_librarian === '0'){
            return true;
        }else{
            return false;
        }
    }

    public function managedBorrows(){
        if(!$this->isLibrarian()) return false;
        return Borrow::all()->where('request_managed_by', $this->id);
    }

    public function hasBorrows(){
        if($this->isLibrarian()) return false;
        $accepted = Borrow::all()->where('reader_id', $this->id)->where('status','ACCEPTED');
        $returned = Borrow::all()->where('reader_id', $this->id)->where('status','RETURNED')->pluck('book_id');

        return $accepted->whereNotIn('book_id', $returned);
        //return Borrow::all()->where('reader_id', $this->id)->where('status', );
    }
}
