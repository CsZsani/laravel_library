<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Genre;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function profile(){
        if(Auth::user()->is_librarian === '1'){
            $first = count(Borrow::where('status', 'ACCEPTED')->where('request_managed_by', Auth::id())->get());
            $second = count(Borrow::where('status', 'REJECTED')->where('request_managed_by', Auth::id())->get());
            $third = count(Borrow::where('status', 'RETURNED')->where('return_managed_by', Auth::id())->get());
        }else{
            $first = Borrow::allBorrowOfUser(Auth::id());
            $second = Borrow::booksAtReader(Auth::id());
            $third = Borrow::lateBooksAtReader(Auth::id());
        }
        return view('profile', ['user' => Auth::user(), 'first' => $first, 'second' => $second, 'third' => $third]);
    }
}
