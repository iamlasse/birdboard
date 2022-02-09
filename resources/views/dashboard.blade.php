@extends('layouts.app')

@section('content')
        <div class="py-12">
        
        <div class="alert-wrapper" x-data="{showUpdates: @entangle('showProjectUpdatedNotification').defer }">
    <div class="test bg-green-200 text-gray-700 p-10" x-show="showUpdates">
      <div class="span" >Project Updated</div>
    </div>
  </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 md:w-full">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h1 class="text-9xl">Projects</h1>
                  <livewire:data-table />                
                </div>
            </div>
        </div>
@endsection
