@extends('layouts.app')
@section('title', 'Új műfaj')

@section('content')
<div class="container">
    <h1>Új műfaj</h1>
    <p class="mb-1">Ezen az oldalon tudsz új műfajt létrehozni. A könyveket úgy tudod hozzárendelni, ha a műfaj létrehozása után módosítod a könyvet, és ott bejelölöd ezt a műfajt is.</p>
    <div class="mb-4">
        <a href="{{ route('books.index') }}" id="all-books-ref"><i class="fas fa-long-arrow-alt-left"></i> Vissza a könyvekhez</a>
    </div>

    @if (Session::has('genre-created'))
        <div class="alert alert-success" role="alert" id="genre-created">
            A(z) <span id="genre-name"><strong>{{ Session::get('genre-created') }}</strong></span> nevű műfaj sikeresen létre lett hozva!
        </div>
    @endif

    <form action="{{ route('genres.store') }}" novalidate method="POST">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Műfaj neve" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Stílus*</label>
            <div class="col-sm-10">
                @foreach ($styles as $style)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="style" id="style-{{ $style }}" value="{{ $style }}" {{ old('style') === $style ? 'checked' : '' }}>
                        <label class="form-check-label" for="style-{{ $style }}">
                            <span class="badge badge-{{ $style }}">{{ $style }}</span>
                        </label>
                    </div>
                @endforeach
                @error('style')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection