<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borrows = Borrow::where('reader_id', Auth::id())->where('status', 'PENDING')->orderBy('created-at', 'desc')->paginate(15);
        $lates = count( Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->get());
        return redirect()->route('reader.borrow.pending', ['borrows' => $borrows, 'lates' => $lates]);
    
    }

    public function pending()
    {
        $borrows = Borrow::where('reader_id', Auth::id())->where('status', 'PENDING')->orderBy('created-at', 'desc')->paginate(15);
        $lates = count( Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->get());
        return view('reader.borrow.pending', ['borrows' => $borrows, 'lates' => $lates]);
    }

    public function accepted()
    {
        $borrows = Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->orderBy('created-at', 'desc')->paginate(15);
        $lates = count( Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->get());
        return view('reader.borrow.accepted', ['borrows' => $borrows, 'lates' => $lates]);
    }

    public function rejected()
    {
        $borrows = Borrow::where('reader_id', Auth::id())->where('status', 'REJECTED')->orderBy('created-at', 'desc')->paginate(15);
        $lates = count( Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->get());
        return view('reader.borrow.rejected', ['borrows' => $borrows, 'lates' => $lates]);
    }

    public function late()
    {
        $borrows = Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->paginate(15);;
        $lates = count( Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->get());
        return view('reader.borrow.late', ['borrows' => $borrows, 'lates' => $lates]);
    }

    public function returned()
    {
        $borrows = Borrow::where('reader_id', Auth::id())->where('status', 'RETURNED')->orderBy('created-at', 'desc')->paginate(15);
        $lates = count( Borrow::where('reader_id', Auth::id())->where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->get());
        return view('reader.borrow.returned', ['borrows' => $borrows, 'lates' => $lates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        error_log("ott");

        $this->authorize('create', Borrow::class);
        $validated = $request->validate(
            [
                'book' => 'required',
            ],
        );
        $book = $validated['book'];
        error_log($book);
        //$borrows = Borrow::all();
        return view('reader.borrow.create', ['book' => Book::find($book)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Borrow::class);
        $validated = $request->validate(
            [
                'book' => 'required',
            ],
        );
        $book = $validated['book'];
        //error_log($book);
        //error_log(Book::find($book));
        $validated['status'] = 'PENDING';
        $validated['reader_id'] = Auth::id();
        $validated['book_id'] = $book;
        Borrow::create($validated);

        $request->session()->flash('borrow-created');
        return redirect()->route('books.show',$book);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $borrow = Borrow::find($id);
        return view('reader.borrow.show', ['borrow' => $borrow]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
