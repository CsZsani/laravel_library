@extends('layouts.app')
@section('title', 'Könyvek')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Üdvözlünk a könyvtárban!</h1>
            <h3 class="mb-1">Minden elérhető könyv</h3>
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
</div>
@endsection