@extends('layouts.admin-layout')

@section('content')
    <realignment-create-edit :id="{{ $id }}" :prop-user='@json($user)'></realignment-create-edit>
@endsection

