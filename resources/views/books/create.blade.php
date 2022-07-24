@extends('layouts.app')
@section('title', 'Új könyv hozzáadása')

@section('content')
<div class="container">
    <h1>Új könyv hozzáadása</h1>
    <p class="mb-1">Ezen az oldalon tudsz új könyvet hozzáadni.</p>
    <div class="mb-4">
        <a href="{{ route('books.index') }}" id="all-books-ref"><i class="fas fa-long-arrow-alt-left"></i> Vissza a könyvekhez</a>
    </div>

    @if (Session::has('book-created'))
        <div class="alert alert-success" role="alert" id="book-created">
            A(z) <span id="book-title"><strong>{{ Session::get('book-created') }}</strong></span> című könyv sikeresen hozzá lett adva!
        </div>
    @endif

    <form action="{{ route('books.store') }}" novalidate method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label">Cím*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Könyv címe" value="{{ old('title') }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label">Szerzők*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('authors') is-invalid @enderror" id="authors" name="authors" placeholder="Könyv szerzői" value="{{ old('authors') }}">
                @error('authors')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label">Kiadás dátuma*</label>
            <div class="col-sm-10">
                <input type="date" class="form-control @error('released_at') is-invalid @enderror" id="released_at" name="released_at" value="{{ old('released_at') }}">
                @error('released_at')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="text" class="col-sm-2 col-form-label">Oldalak száma*</label>
            <div class="col-sm-10">
                <input type="number" id="pages" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') }}"">
                @error('pages')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="text" class="col-sm-2 col-form-label">ISBN*</label>
            <div class="col-sm-10">
                <input type="text" id="isbn" class="form-control @error('isbn') is-invalid @enderror" placeholder="ISBN szám" name="isbn" value="{{ old('isbn') }}"">
                @error('isbn')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="text" class="col-sm-2 col-form-label">Leírás</label>
            <div class="col-sm-10">
                <textarea id="description" rows="5" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Rövid leírás a könyvről">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Műfajok</label>
            <div class="col-sm-10">
                <div class="row">
                    @forelse ($genres->chunk(5) as $genreChuck)
                        <div class="col-6 col-md-3 col-lg-2">
                            @foreach ($genreChuck as $genre)
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        value="{{ $genre->id }}"
                                        id="genre{{ $loop->iteration }}"
                                        name="genres[]"
                                        @if (is_array(old('genres')) && in_array($genre->id, old('genres')))
                                            checked
                                        @endif
                                    >
                                    <label for="genre{{ $loop->iteration }}" class="form-check-label">
                                        <span class="badge badge-{{ $genre->style }}">{{ $genre->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>Nincsenek műfajok</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="attachment" class="col-sm-2 col-form-label">Borítókép</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <input type="file" class="form-control-file @error('attachment') is-invalid @enderror" id="attachment" name="attachment">
                    @error('attachment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="text" class="col-sm-2 col-form-label">Készleten*</label>
            <div class="col-sm-10">
                <input type="number" id="in_stock" class="form-control @error('in_stock') is-invalid @enderror" name="in_stock" value="{{ old('in_stock') }}"">
                @error('in_stock')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection