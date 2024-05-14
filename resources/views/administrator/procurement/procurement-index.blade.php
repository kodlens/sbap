@extends('layouts.admin-layout')

@section('content')
    @auth
        <procurement-index :prop-user="{{ Auth::user() }}"></procurement-index>
    @endauth
@endsection

