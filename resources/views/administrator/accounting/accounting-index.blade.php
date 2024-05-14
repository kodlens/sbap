@extends('layouts.admin-layout')

@section('content')
    @auth
        <accounting-index :prop-user="{{ Auth::user() }}"></accounting-index>
    @endauth
@endsection

