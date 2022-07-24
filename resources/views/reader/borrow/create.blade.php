@extends('layouts.app')
@section('title', 'Új kölcsönzés benyújtása')

@section('content')
<div class="container">
    <h1>Kölcsönzés</h1>
    <div class="mb-4">
        <a href="{{ route('books.show', $book) }}" id="all-books-ref"><i class="fas fa-long-arrow-alt-left"></i> Vissza a könyv adatlapjára</a>
    </div>

    {{-- @if (Session::has('book-created'))
        <div class="alert alert-success" role="alert" id="book-created">
            A(z) <span id="book-title"><strong>{{ Session::get('book-created') }}</strong></span> című könyv sikeresen hozzá lett adva!
        </div>
    @endif --}}

    <div class="container">
        <div class="row">
            <div class="col-3 mb-3 d-flex align-items-strech">
        
                <div class="card w-100 book">
                    
                    <div class="card-body">
                        <div class="mb-2">
                            <img class="card-img-top" src="{{$book->cover_image === null ? $book->getCover() : asset('images/book_covers/' . $book->getCover())}}" alt="Könyv borítója.">
                            <h5 class="card-title mb-0 book-title"><b>{{ $book->title }}</b></h5>
                            <small class="text-secondary">
                                <span class="mr-2">
                                    <i class="fas fa-user"></i>
                                    <span class="book-author">{{ $book->authors ? $book->authors : 'Nincs szerző' }}</span>
                                </span>
                                <span class="mr-2">
                                    <i class="far fa-calendar-alt"></i>
                                    <span class="book-date">{{ date('Y',strtotime($book->released_at))}}</span>
                                </span>
                            </small>
                            <p class="card-text book-description">{{ $book->description ? $book->description : '' }}</p>
                        </div>
                        <p class="card-text">{{ Str::of($book->text)->limit(32) }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('books.show', $book) }}" class="btn btn-primary book-details">Adatlap <i class="fas fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
            @if ($book->isBorrowedByReader(Auth::id()))
                <div class="col-9">
                    <div class="alert alert-danger" role="alert">
                        <strong>Figyelem!</strong> Erre a könyvre nem nyújthatsz be további kölcsönzési igényeket, mivel a nyilvántartás szerint már benyújtottál egyet / a könyv éppen nálad van.
                    </div>
                    <a href="{{ route('books.show', $book) }}" id="back-to-book-btn" role="button" class="btn btn-sm btn-dark mb-1">Vissza</a>
                </div>
            @else
                <div class="col-9">
                    <div class="alert alert-primary" role="alert">
                        Ezen az oldalon tudod benyújtani a kölcsönzésedet az alőbb látott könyvre. <strong>Figyelem!</strong>
                        <strong>Ezzel csak egy kölcsönzési igény jön létre, amelyet a könyvtárosoknak még jóvá kell hagyniuk!</strong>
                    </div>
                    <h3>Készletinformáció</h3>
                    @if ($book->isAvailable())
                        <p style="color: #09bd12;"><b>Ez a könyv jelenleg elérhető a könyvtár készletében!</b></p>
                    @else
                        <p style="color: #c90902;"><b>Ez a könyv jelenleg nem érhető el a könyvtár készletében!</b></p>
                    @endif
                    <h3>Kölcsönzés</h3>
                    <p>Itt benyújthatod a kölcsönzési igényedet.</p>
                    <form action="{{route('reader.borrow.store')}}" method="post">
                        @csrf
                        <input type="text" name="book" id="book" hidden value="{{$book->id}}">
                        <button type="submit" class="btn btn-success mr-2">Mentés</button>
                        <button type="reset" class="btn btn-danger">Mégsem</button>
                    </form>
                </div>
            @endif
            
        </div>
    </div>
@endsection