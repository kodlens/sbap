@extends('layouts.admin-layout')

@section('content')


    @if ($id > 0)
        <procurement-create-edit :id="{{ $id}}"></procurement-create-edit>
    @else
        <procurement-create-edit :id={{ $id }}></procurement-create-edit>
    @endif
    
@endsection

