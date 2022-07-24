@extends('layouts.app')
@section('title', 'Könyvek')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Üdvözlünk a könyvtárban!</h1>
            <h3 class="mb-1">Keresés eredménye: {{$searchText}}</h3>
        </div>
        <div class="col-12 col-md-4">
            @auth
                <div class="py-md-3 text-md-right">
                    @can('create', App\Models\Genre::class)
                        <p class="my-1">Elérhető műveletek:</p>
                        <a href="{{ route('genres.create') }}" role="button" class="btn btn-sm btn-success mb-1" id="create-genre-button"><i class="fas fa-plus-circle"></i> Új műfaj</a>
                    @endcan
                    @can('create', App\Models\Book::class)
                        <a href="{{ route('books.create') }}" role="button" class="btn btn-sm btn-success mb-1" id="create-book-btn"><i class="fas fa-plus-circle"></i> Új könyv</a>
                    @endcan
                </div>
            @endauth
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row" id="books">
                @forelse ($books as $book)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-items-strech">
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
                @empty
                @endforelse
            </div>

            <div class="w-100 d-flex justify-content-center">
                {{ $books->links() }}
            </div>


        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Keresés</h5>
                            <p class="small">Könyv keresése cím alapján.</p>
                            <form action="{{ route('books.search') }}" novalidate method="GET" role="search">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchtext" name="searchtext" placeholder="Keresett cím">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Keresés</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body genres-list">
                            <h5 class="card-title mb-2">Műfajok</h5>
                            <p class="small">Könyvek megtekintése egy adott műfajhoz.</p>
                            @forelse ($genres as $genre)
                                <a href="{{route('genres.show', $genre)}}" class="badge badge-{{$genre->style}}">{{ $genre->name }}</a>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Statisztika</h5>
                            <div class="small">
                                <p class="mb-1">Adatbázis statisztika:</p>
                                <ul class="fa-ul">
                                    <li><span class="fa-li"><i class="fas fa-user"></i></span>Felhasználók: <span id="stats-users">{{$usersNum}}</span></li>
                                    <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Műfajok: <span id="stats-genres">{{$genresNum}}</span></li>
                                    <li><span class="fa-li"><i class="fas fa-comments"></i></span>Könyvek: <span id="stats-books">{{$booksNum}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection