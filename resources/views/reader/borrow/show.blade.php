@extends('layouts.app')
@section('title', 'Kölcsönzésem')

@section('content')
<div class="container">
    <h1>Kölcsönzés megtekintése</h1>
    <div class="mb-4">
        <a href="{{ route('librarian.borrow.pending') }}" id="all-books-ref"><i class="fas fa-long-arrow-alt-left"></i> Vissza a kölcsönzésekhez</a>
    </div>
    {{-- @if (Session::has('borrow-updated'))
        <div class="alert alert-success" role="alert" id="borrow-updated">
            Sikeresen módosítottad ezt a kölcsönzést!
        </div>
    @endif --}}
    <div class="row">
        <div class="col-3">
            <div class=" mb-3 d-flex align-items-strech">
                
                <div class="card w-100 book">
                    
                    <div class="card-body">
                        <div class="mb-2">
                            <img class="card-img-top" src="{{$borrow->book->cover_image === null ? $borrow->book->getCover() : asset('images/book_covers/' . $borrow->book->getCover())}}" alt="Könyv borítója.">
                            <h5 class="card-title mb-0 book-title"><b>{{ $borrow->book->title }}</b></h5>
                            <small class="text-secondary">
                                <span class="mr-2">
                                    <i class="fas fa-user"></i>
                                    <span class="book-author">{{ $borrow->book->authors ? $borrow->book->authors : 'Nincs szerző' }}</span>
                                </span>
                                <span class="mr-2">
                                    <i class="far fa-calendar-alt"></i>
                                    <span class="book-date">{{ date('Y',strtotime($borrow->book->released_at))}}</span>
                                </span>
                            </small>
                            <p class="card-text book-description">{{ $borrow->book->description ? $borrow->book->description : '' }}</p>
                        </div>
                        <p class="card-text">{{ Str::of($borrow->book->text)->limit(32) }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('books.show', $borrow->book) }}" class="btn btn-primary book-details">Adatlap <i class="fas fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <div class="col-6 border-left">
        <h3>Adatok</h3>
        <div class="mt-3">
            <div class="container-md">
                <p><b>Kölcsönzés alapadatai</b></p>
                <span class="ml-4"><i class="fas fa-user mx-2"></i><span>Olvasó: <span class="mr-2 text-secondary">{{ $borrow->reader->name}}</span></span><br>
                <span class="ml-4"><i class="fas fa-book mx-2"></i><span>Könyv: <span class="mr-2 text-secondary">{{ $borrow->book->title}}</span></span><br>
                <span class="ml-4"><i class="fas fa-clock mx-2"></i><span>Kölcsönzés kérve ekkor: <span class="mr-2 text-secondary">{{ $borrow->created_at}}</span></span><br>
                <span class="ml-4"><i class="fas fa-question-circle mx-2"></i><span>Állapot: <span class="mr-2 text-secondary" ><span style="color: {{$borrow->statusInColor()}};"><b>{{ $borrow->statusInString()}}</b></span></span><br>
                <p><b>Kölcsönzés feldolgozása</b></p>
                @if ($borrow->request_managed_by)
                <span class="ml-4"><i class="fas fa-clock mx-2"></i><span>Feldolgozva ekkor: <span class="mr-2 text-secondary">{{ $borrow->request_processed_at}}</span></span><br>
                <span class="ml-4"><i class="fas fa-user mx-2"></i><span>Feldolgozta: <span class="mr-2 text-secondary">{{ $borrow->getRequestManager()->name}}</span></span><br>
                <span class="ml-4"><i class="fas fa-comment mx-2"></i><span>Feldolgozó könyvtáros megjegyzése: <span class="mr-2 text-secondary">{{ $borrow->request_processed_message ? $borrow->request_processed_message : 'nincs megjegyzés'}}</span></span><br>
                <span class="ml-4"><i class="fas fa-clock mx-2"></i><span>Határidő: <span class="mr-2 text-secondary">{{ $borrow->deadline ? $borrow->deadline : 'nincs megadva határidó'}}</span></span><br>
                    
                @else
                <span class="ml-4"><span class="mr-2 text-secondary">Nincs információ a feldolgozásról</span></span><br>
                @endif
                <p><b>Visszajuttatás</b></p>
                @if ($borrow->return_managed_by)
                <span class="ml-4"><i class="fas fa-clock mx-2"></i><span>Visszajuttatva ekkor: <span class="mr-2 text-secondary">{{ $borrow->returned_at}}</span></span><br>
                <span class="ml-4"><i class="fas fa-user mx-2"></i><span>Feldolgozta: <span class="mr-2 text-secondary">{{ $borrow->getReturnManager()->name}}</span></span><br>

                @else
                <span class="ml-4"><span class="mr-2 text-secondary">Nincs információ a visszajuttatásról</span></span><br>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

@endsection