@extends('layouts.admin-layout')

@section('content')
    @php

        $user = Auth::user();
        $role = $user->role;
    @endphp
    @if(in_array($role, ['ADMINISTRATOR', 'ACCOUNTING STAFF', 'BUDGET OFFICER']))
        <dashboard></dashboard>
    @else
        <dashboard-user></dashboard-user>
    @endif
  
@endsection
