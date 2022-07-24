<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LibrarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Borrow::class);
        //$borrows = Borrow::all();
        $borrows = Borrow::where('status', 'PENDING')->orderBy('created-at', 'asc')->paginate(15);
        //return view('librarian.borrow.pending', ['borrows' => $borrows]);
        return redirect()->route('librarian.borrow.pending', ['borrows' => $borrows]);
    }

    public function pending()
    {
        $this->authorize('viewAny', Borrow::class);
        $borrows = Borrow::where('status', 'PENDING')->orderBy('created-at', 'asc')->paginate(15);
        return view('librarian.borrow.pending', ['borrows' => $borrows]);
    }

    public function accepted()
    {
        $this->authorize('viewAny', Borrow::class);
        $borrows = Borrow::where('status', 'ACCEPTED')->orderBy('created-at', 'asc')->paginate(15);
        return view('librarian.borrow.accepted', ['borrows' => $borrows]);
    }

    public function rejected()
    {
        $this->authorize('viewAny', Borrow::class);
        $borrows = Borrow::where('status', 'REJECTED')->orderBy('created-at', 'asc')->paginate(15);
        return view('librarian.borrow.rejected', ['borrows' => $borrows]);
    }

    public function late()
    {
        $this->authorize('viewAny', Borrow::class);
        $borrows = Borrow::where('status', 'ACCEPTED')->where('deadline', '<', date_create('now'))->paginate(15);;
        return view('librarian.borrow.late', ['borrows' => $borrows]);
    }

    public function returned()
    {
        $this->authorize('viewAny', Borrow::class);
        $borrows = Borrow::where('status', 'RETURNED')->orderBy('created-at', 'asc')->paginate(15);
        return view('librarian.borrow.returned', ['borrows' => $borrows]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Borrow::find($id));
        $borrow = Borrow::find($id);
        return view('librarian.borrow.show', ['borrow' => $borrow]);
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
    public function update(Request $request, Borrow $borrow)
    {
        $this->authorize('update', Borrow::find($borrow->id));
        error_log($request);
        $validated = $request->validate(
            // Validation rules
            [
                'status' => 'required',
                'deadline' => 'nullable|date',
                'request_processed_message' => 'nullable|max:255'
            ],
            [
                'required' => 'A mező megadása kötelező.',
                'request_processed_message.max' => 'Maximum 255 karaktert írjon be!'
            ]
        );

        
        if($validated['status'] === 'REJECTED'){
            $validated['request_processed_at'] = date_create('now');
            $validated['request_managed_by'] = Auth::id();
        }
        elseif($validated['status'] === 'ACCEPTED'){
            $validated['request_processed_at'] = date_create('now');
            $validated['request_managed_by'] = Auth::id();
        }
        elseif($validated['status'] === 'RETURNED'){
            $validated['returned_at'] = date_create('now');
            $validated['return_managed_by'] = Auth::id();
        }

        $b = Borrow::find($borrow->id)->update($validated);
        error_log($borrow);error_log($borrow->id);error_log(Borrow::find($borrow->id));

        $request->session()->flash('borrow-updated');
        return redirect()->route('librarian.borrow.show', ['borrow' => Borrow::find($borrow->id)]);
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
