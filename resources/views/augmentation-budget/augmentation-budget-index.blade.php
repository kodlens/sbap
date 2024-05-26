@extends('layouts.admin-layout')

@section('content')
    <augmentation-budget-index :prop-user='@json($user)'></augmentation-budget-index>
@endsection

