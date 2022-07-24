@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1 id="book-title">{{ $book->title }}</h1>

            {{-- <div class="d-flex my-1 text-secondary">
                
                <span class="mr-2">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ $book->created_at->format('Y. m. d.') }}</span>
                </span>
            </div> --}}

            <div class="mb-2" id="book-categories">
                @foreach($book->genres as $genre)
                    <a href="{{route('genres.show', $genre)}}" class="badge badge-{{ $genre->style }}">{{ $genre->name }}</a>
                @endforeach
            </div>

            <div class="mb-3">
                <a href="{{ route('books.index') }}" id="all-books-ref"><i class="fas fa-long-arrow-alt-left"></i> Minden könyv</a>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right" id="book-actions">
                @can('update', $book)
                    <p class="my-1">Könyv kezelése:</p>
                    <a href="{{ route('books.edit', $book) }}" id="edit-book-btn" role="button" class="btn btn-sm btn-primary mb-1"><i class="far fa-edit"></i> Módosítás</a>
                @endcan
                @can('delete', $book)
                    <form action="{{route('books.destroy', $book)}}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger" id="delete-book-btn"><i class="far fa-trash-alt"></i> Törlés</button>
                    </form>
                @endcan
                @can('create', App\Models\Borrow::class)
                    <form action="{{route('reader.borrow.create')}}" method="GET">
                        @csrf
                        <p class="my-1">Műveletek:</p>
                        <input type="text" name="book" id="book" hidden value="{{$book->id}}">
                        <button id="create-borrow-btn" role="button" class="btn btn-sm btn-primary mb-1"><i class="far fa-edit"></i> Kölcsönzés</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
    @if (Session::has('borrow-created'))
            <div class="alert alert-success" role="alert" id="borrow-created">
                Sikeresen benyújtottad a kölcsönzési igényedet erre a könyvre! A kölcsönzéseidet megtekintheted a Kölcsönzéseim oldalon!
            </div>
        @endif
    <div class="mt-3">
        <h3>Könyv adatai</h3>
        <div class="container-md">
            <span class="mr-2"><i class="fas fa-user mx-2"></i><span>Szerzők: <span class="mr-2 text-secondary" id="book-authors">{{ $book->authors ? $book->authors : 'Nincs szerző' }}</span></span><br>
            <span class="mr-2"><i class="fas fa-calendar mx-2"></i><span>Kiadás dátuma: <span class="mr-2 text-secondary" id="book-date">{{ date('Y. m. d.',strtotime($book->released_at)) }}</span></span><br>
            <span class="mr-2"><i class="fas fa-file mx-2"></i><span>Oldalak száma: <span class="mr-2 text-secondary" id="book-pages">{{ $book->pages }}</span></span><br>
            <span class="mr-2"><i class="fas fa-language mx-2"></i><span>Nyelv: <span class="mr-2 text-secondary" id="book-lang">{{ $book->language_code }}</span></span><br>
            <span class="mr-2"><i class="fas fa-database mx-2"></i><span>ISBN: <span class="mr-2 text-secondary" id="book-isdn">{{ $book->isbn }}</span></span><br>
            <span class="mr-2"><i class="fas fa-sign-in-alt mx-2"></i><span>Készletszám: <span class="mr-2 text-secondary" id="book-in-stock">{{ $book->in_stock }}</span></span><br>
            <span class="mr-2"><i class="fas fa-sign-out-alt mx-2"></i><span>Jelenleg kikölcsönözve: <span class="mr-2 text-secondary" id="book-borrowed">{{ $book->in_stock - $book->getAvailability()}}</span></span><br>
           
        </div>
    </div>

    <div class="mt-3">
        <h3>Könyv leírása</h3>
        <p>{{ $book->description ? $book->description : 'Nem elérhető.' }}</p>
    </div>
</div>
@endsection