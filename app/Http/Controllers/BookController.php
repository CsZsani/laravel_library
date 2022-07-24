<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class BookController extends Controller
{

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
        $books = Book::paginate(9);
        $genres = Genre::all();
        $booksNum = Book::all()->count();
        $genresNum = Genre::all()->count();
        $usersNum = User::all()->count();
        return view('books.index', ['books' => $books, 'genres' => $genres, 'booksNum' => $booksNum, 'genresNum' => $genresNum, 'usersNum' => $usersNum]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Book::class);
        $genres = Genre::all();
        return view('books.create', ['genres' => $genres]);
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
            // Validation rules
            [
                'title' => 'required|min:3|max:255',
                'authors' => 'required|min:3|max:255',
                'released_at' => 'required|date|before:now',
                'pages' => 'required|integer|min:1',
                'isbn' => 'required|regex:/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/i',
                'description' => 'nullable',
                'genres' => 'nullable',
                'genres.*' => 'integer|distinct|exists:genres,id',
                'attachment' => 'nullable|file|mimes:png,jpg|max:1024',
                'in_stock' => 'required|integer|min:0|max:3000'
            ],
            [
                'required' => 'A mező megadása kötelező.',
                'title.min' => 'A mező legalább :min hosszú legyen.',
                'authors.min' => 'A mező legalább :min hosszú legyen.',
                'min' => 'Minimum :min mennyiséget írjon be.'
            ]
        );

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            //$validated['cover_image_original_name'] = $file->getClientOriginalName();
            //$console_out->writeln($file_name);
            $validated['cover_image'] = $file->hashName();
            //$console_out->writeln($hash_name);
            Storage::disk('book_covers')->put($validated['cover_image'], $file->get());
        }

        //$validated['author_id'] = Auth::id();

        $book = Book::create($validated);

        $book->genres()->attach($request->genres);

        $request->session()->flash('book-created', $book->title);
        return redirect()->route('books.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        error_log('Some message here showban.');
        return view('books.show', ['book' => Book::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Book::find($id));
        $bg =  Book::find($id)->genres()->pluck('id')->toArray();
        return view('books.edit', ['genres' => Genre::all(), 'book' => Book::find($id), 'book_genres' => $bg]);
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
        $validated = $request->validate(
            // Validation rules
            [
                'title' => 'required|min:3|max:255',
                'authors' => 'required|min:3|max:255',
                'released_at' => 'required|date|before:now',
                'pages' => 'required|integer|min:1',
                'isbn' => 'required|regex:/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/i',
                'description' => 'nullable',
                'genres' => 'nullable',
                'genres.*' => 'integer|distinct|exists:genres,id',
                'attachment' => 'nullable|file|mimes:png,jpg|max:1024',
                'remove_cover' => 'nullable|boolean',
                'in_stock' => 'required|integer|min:0|max:3000'
            ],
            [
                'required' => 'A mező megadása kötelező.',
                'title.min' => 'A mező legalább :min hosszú legyen.',
                'authors.min' => 'A mező legalább :min hosszú legyen.',
                'min' => 'Minimum :min mennyiséget írjon be.'
            ]
        );

        if ($request->hasFile('attachment')) {
            Storage::disk('book_covers')->delete(Book::find($id)->cover_image);
            $file = $request->file('attachment');
            //$validated['cover_image_original_name'] = $file->getClientOriginalName();
            //$console_out->writeln($file_name);
            $validated['cover_image'] = $file->hashName();
            //$console_out->writeln($hash_name);
            Storage::disk('book_covers')->put($validated['cover_image'], $file->get());
        }

        if(array_key_exists('remove_cover',$validated)){
            if($validated['remove_cover'] === true || $validated['remove_cover'] === "1"){
                Storage::disk('book_covers')->delete(Book::find($id)->cover_image);
                $validated['cover_image'] = null;
            }
            
        }

        //$validated['author_id'] = Auth::id();

        //$book = Book::create($validated);
        $book = Book::find($id)->update($validated);
        //$book->genres()->attach($request->genres);
        Book::find($id)->genres()->sync($request->genres);

        $request->session()->flash('book-updated', Book::find($id)->title);
        return redirect()->route('books.edit', Book::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Book::find($id));
        //$this->authorize('delete', $book);
        Borrow::where('book_id', $id)->delete();

        Book::find($id)->delete();
        // DB::table('books')->where('id', $id)->delete();
        Book::destroy($id);
        //Borrow::all()->where('book_id', $id)->destroy();
        //$book->delete();
        return redirect()->route('books.index');
    }

    public function search(Request $request){
        $text = $request->input('searchtext');
        $books = Book::where('title', 'like', '%' . $text . '%')->paginate(9);
        error_log('Some message here.');
        $genres = Genre::all();
        $booksNum = Book::all()->count();
        $genresNum = Genre::all()->count();
        $usersNum = User::all()->count();
        return view('books.search', ['searchText' => $text, 'books' => $books, 'genres' => $genres, 'booksNum' => $booksNum, 'genresNum' => $genresNum, 'usersNum' => $usersNum]);
    }
}
