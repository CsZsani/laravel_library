@extends('layouts.app')
@section('title', 'Könyvek')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1><span id="genre" class="badge badge-{{$genre->style}}">{{ $genre->name }}</span> műfaj könyvei</h1>
            <div class="mb-3">
                <a href="{{ route('books.index') }}" id="all-books-ref"><i class="fas fa-long-arrow-alt-left"></i> Minden könyv</a>
            </div>
        </div>
       
        {{-- <div class="col-12 col-md-4">
            @auth
                <div class="py-md-3 text-md-right">
                    <p class="my-1">Elérhető műveletek:</p>
                    <a href="{{ route('genres.create') }}" role="button" class="btn btn-sm btn-success mb-1" id="create-genre-button"><i class="fas fa-plus-circle"></i> Új műfaj</a>
                    <a href="{{ route('genres.create') }}" role="button" class="btn btn-sm btn-success mb-1" id="create-genre-btn"><i class="fas fa-plus-circle"></i> Új könyvek</a>
                </div>
            @endauth
        </div> --}}
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right" id="genre-actions">
                @can('update', $genre)
                    <p class="my-1">Műfaj kezelése:</p>
                    <a href="{{ route('genres.edit', $genre) }}" id="edit-genre-btn" role="button" class="btn btn-sm btn-primary mb-1"><i class="far fa-edit"></i> Módosítás</a>
                @endcan
                @can('delete', $genre)
                    <form action="{{route('genres.destroy', $genre)}}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger" id="delete-genre-btn" value="delete"><i class="far fa-trash-alt"></i> Törlés</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row" id="books">
                @forelse ($books as $book)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-items-strech">
                        <div class="card w-100 books">
                            <div class="card-body">
                                <div class="mb-2">
                                    <img class="card-img-top" src="{{$book->getCover()}}" alt="">
                                    <h5 class="card-title mb-0 book-title"><b>{{ $book->title }}</b></h5>
                                    <small class="text-secondary">
                                        <span class="mr-2 book-author">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $book->authors ? $book->authors : 'Nincs szerző' }}</span>
                                        </span>
                                        <span class="mr-2 book-date">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ date('Y',strtotime($book->released_at))}}</span>
                                        </span>
                                    </small>
                                    <p class="card-text book-description">{{ $book->description ? $book->description : '' }}</p>
                                </div>
                                <p class="card-text">{{ Str::of($book->text)->limit(32) }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-primary genre-details">Adatlap <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>

            

            <!--
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Előző</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Következő</a>
                    </li>
                </ul>
            </nav>
            -->

        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                {{-- <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Keresés</h5>
                            <p class="small">Könyv keresése cím alapján.</p>
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Keresett cím">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Keresés</button>
                            </form>
                        </div>
                    </div>
                </div> --}}

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