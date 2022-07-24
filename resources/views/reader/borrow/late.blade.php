@extends('layouts.app')
@section('title', 'Kölcsönzések')

@section('content')
<div class="container">
  @if ($lates > 0)
  <div class="alert alert-danger" role="alert">
    <strong>Figyelem!</strong> Jelenleg <strong>{{$lates}}</strong> olyan kölcsönzésed van, aminek lejárt már a határideje, de még nem juttattad vissza!
  </div>
  @endif
  <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('reader.borrow.pending') }}">📙 Igények</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('reader.borrow.rejected') }}">❌ Elutasítva</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('reader.borrow.accepted') }}">✔ Elfogadva</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('reader.borrow.returned') }}">📗 Visszahozva</a>
      </li>
      <li class="nav-item">
          <a class="nav-link active" href="{{ route('reader.borrow.late') }}">⌛ Késés @if ($lates > 0)<b style="color: #c90902;">{{$lates}}</b>@endif</a>
      </li>
  </ul>
    <div class="col-12 col-md-8 mb-2">
        <h3 class="mb-1">Késések</h3>
    </div>
    <div class="alert alert-primary" role="alert">
        Ezek a könyvek még nálad vannak, nem juttattad vissza a könyvtárnak a határidő előtt.
    </div>
    <table class="table">
        <tbody>
        @forelse ($borrows as $borrow)
            <tr>
              <td>Könyv címe: <b>{{$borrow->book->title}}</b></td>
              <td class="mr-0 py-0 text-right">
                  <small class="text-secondary"><span class="mr-0">
                    <span class="book-date">Kérve: {{ $borrow->created_at}}</span>
                    </span>
                  </small><br>
                  <a href="{{route('reader.borrow.show', $borrow)}}" id="show-borrows-btn" role="button" class="btn btn-sm btn-primary mb-1">Részletek</a>
              </td>
            </tr>
        @empty
            
        @endforelse
        </tbody>
      </table>
      <div class="w-100 d-flex justify-content-center">
        {{ $borrows->links() }}
    </div>
</div>
@endsection