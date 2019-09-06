@extends('layout')

@section('content')
    <h1>Contact</h1>
    <p>Hello this is contact!</p> 

    @can('home.secret')
        <p>Special contact details</p>
    @endcan
@endsection

