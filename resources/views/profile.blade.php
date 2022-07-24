@extends('layouts.app')
@section('title', 'Profil megtekintése')

@section('content')
<div class="container">
    <h1>Profil</h1>
    <h3>{{$user->name}} @if ($user->isLibrarian()) <span class="badge badge-danger">Könyvtáros</span> @else <span class="badge badge-success">Olvasó</span> @endif</h3>
    @if ($user->isReader())
    <span class="ml-4"><i class="fas fa-envelope mx-2"></i><span>E-mail: <span class="mr-2 text-secondary">{{ $user->email}}</span></span><br>
    <span class="ml-4"><i class="fas fa-clock mx-2"></i><span>Regisztrált: <span class="mr-2 text-secondary">{{ $user->created_at}}</span></span><br>
    <span class="ml-4"><i class="fas fa-book mx-2"></i><span>Összes kölcsönzés: <span class="mr-2 text-secondary">{{ $first}}</span></span><br>
    <span class="ml-4"><i class="fas fa-book mx-2"></i><span>Könyvek nálad: <span class="mr-2 text-secondary" >{{$second}}<span> ({{ $third}} késés)</span></span><br>
    @else
    <span class="ml-4"><i class="fas fa-envelope mx-2"></i><span>E-mail: <span class="mr-2 text-secondary">{{ $user->email}}</span></span><br>
    <span class="ml-4"><i class="fas fa-clock mx-2"></i><span>Regisztrált: <span class="mr-2 text-secondary">{{ $user->created_at}}</span></span><br>
    <span class="ml-4"><i class="fas fa-book mx-2"></i><span>Általad elfogadott kölcsönzések: <span class="mr-2 text-secondary">{{ $first}}</span></span><br>
    <span class="ml-4"><i class="fas fa-book mx-2"></i><span>Altalad elutasított kölcsönzések: <span class="mr-2 text-secondary" >{{$second}}</span><br>
    <span class="ml-4"><i class="fas fa-book mx-2"></i><span>Altalad visszajuttatott kölcsönzések: <span class="mr-2 text-secondary" >{{$third}}</span><br>
    @endif
</div>
@endsection