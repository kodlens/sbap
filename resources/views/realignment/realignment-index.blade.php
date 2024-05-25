@extends('layouts.admin-layout')

@section('content')
    <realignment-index :prop-user='@json($user)'></realignment-index>
@endsection

