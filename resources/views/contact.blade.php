@extends('layout')

@section('content')
    <h1>Contact</h1>
    <p>Hello this is contact!</p> 

    @can('home.secret')
        <a href="{{ route('secret') }}">
            Special contact details
        </a>
    @endcan
@endsection

