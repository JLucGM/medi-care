@extends('layouts.app')
@section('title')
    {{ __('messages.visits') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            @if(getLogInUser()->hasRole('doctor'))
                <livewire:doctor-visit-table/>
            @else
                <livewire:visit-table/>
            @endif
        </div>
    </div>
@endsection

<!-- Admin can add / delete patient visits records from here. 
 Patient visits are the records who will be entered by the doctors to track 
 patients visits and the other information about their patients. -->