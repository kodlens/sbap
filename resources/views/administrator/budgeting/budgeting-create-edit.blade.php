@extends('layouts.admin-layout')

@section('content')


    @if ($id > 0)
        <budgeting-create-edit :id="{{ $id}}"></budgeting-create-edit>
    @else
        <budgeting-create-edit :id={{ $id }}></budgeting-create-edit>
    @endif
    
@endsection

