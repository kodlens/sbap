@extends('layouts.admin-layout')

@section('content')
@auth
        <budgeting-index :prop-user="{{ Auth::user() }}"></budgeting-index>
    @endauth

    <></>
@endsection

