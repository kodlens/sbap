@extends('layouts.admin-layout')

@section('content')


    @if ($id > 0)
        <accounting-create-edit :id="{{ $id}}"></accounting-create-edit>
    @else
        <accounting-create-edit :id={{ $id }}></accounting-create-edit>
    @endif
    
@endsection

