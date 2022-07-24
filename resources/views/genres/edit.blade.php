@extends('layouts.app')
@section('title', 'Műfaj szerkesztése: '.$genre->name)

@section('content')
<div class="container">
    <h1>Műfaj szerkesztése</h1>
    <p class="mb-1">Ezen az oldalon tudsz műfajt szerkeszteni. A könyveket úgy tudod hozzárendelni, ha a műfaj szerkesztése után módosítod a könyvet, és ott bejelölöd ezt a műfajt is.</p>
    <div class="mb-4">
        <a href="{{ route('books.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a könyvekhez</a>
    </div>

    @if (Session::has('genre-updated'))
        <div class="alert alert-success" role="alert" id="genre-updated">
            A(z) <span id="genre-name"><strong>{{ Session::get('genre-updated') }}</strong></span> nevű műfaj sikeresen frissítve lett!
        </div>
    @endif

    <form action="{{ route('genres.update', $genre) }}" novalidate method="POST">
        @method('PATCH')
        @csrf
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Műfaj neve" value="{{ old('name') ? old('name') : $genre->name }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stílus* {{ old('style') }} </label>
            <div class="col-sm-10">
                @foreach ($styles as $style)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="style" id="style-{{ $style }}" value="{{ $style }}" {{ old('style') === $style ? 'checked' : ($genre->style === $style && !old('style') ? 'checked' : '') }}>
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
            <button type="submit" class="btn btn-primary">Frissít</button>
        </div>
    </form>
</div>
@endsection