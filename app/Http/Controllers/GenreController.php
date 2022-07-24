<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    const styles = ['primary','secondary','success','danger','warning','info','light','dark']; // 'dark'

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Genre::class);
        return view('genres.create', ['styles' => self::styles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3|max:255',
                'style' => 'required|in:'.join(",", self::styles),
            ],
            [
                //'required' => 'A(z) :attribute mező megadása kötelező.',
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        $genre = Genre::create($validated);

        $request->session()->flash('genre-created', $genre->name);
        return redirect()->route('genres.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booksNum = Book::all()->count();
        $genresNum = Genre::all()->count();
        $usersNum = User::all()->count();
        return view('genres.show', ['genre' => Genre::find($id), 'genres' => Genre::all(), 'books' => Genre::find($id)->getBooks(),
        'booksNum' => $booksNum, 'genresNum' => $genresNum, 'usersNum' => $usersNum
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Genre::find($id));
        return view('genres.edit', ['styles' => self::styles, 'genre' => Genre::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3|max:20',
                'style' => 'required|in:'.join(",", self::styles),
            ],
            // Custom messages
            [
                //'required' => 'A(z) :attribute mező megadása kötelező.',
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        //Genre::find($id)->update($validated);
        //$genre = Genre::find(1);
        $genre->update($validated);
        $request->session()->flash('genre-updated', $genre->name);
        return redirect()->route('genres.edit', $genre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Genre::find($id));
        Genre::find($id)->delete();
        //DB::table('genres')->where('id', $id)->delete();
        Genre::destroy($id);
        return redirect()->route('books.index');
    }
}
