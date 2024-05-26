@extends('layouts.admin-layout')

@section('content')
    <augmentation-budget-create-edit :id="{{ $id }}" :prop-user='@json($user)'></augmentation-budget-create-edit>
@endsection

