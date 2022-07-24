@extends('layouts.app')
@section('title', 'Könyvek')

@section('content')
<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('librarian.borrow.pending') }}">📙 Igények</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('librarian.borrow.rejected') }}">❌ Elutasítva</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('librarian.borrow.accepted') }}">✔ Elfogadva</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('librarian.borrow.returned') }}">📗 Visszahozva</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('librarian.borrow.late') }}">⌛ Késés</a>
        </li>
      </ul>
      <div class="col-12 col-md-8 mb-2">
        <h3 class="mb-1">Visszahozott könyvek</h3>
    </div>
    <div class="alert alert-primary" role="alert">
        Ezek a kölcsönzések már le lettek zárva, mert a kölcsönző visszahozta a könyvet.
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
                  <a href="{{route('librarian.borrow.show', $borrow)}}" id="edit-book-btn" role="button" class="btn btn-sm btn-primary mb-1">Kezelés</a>
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