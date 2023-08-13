@extends('layouts/app', ['activePage' => 'general', 'activeButton' => 'settings', 'title' => 'General', 'navName' => 'General'])

@section('content')
<div class="content">
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    @include('alerts.success')
                    @include('alerts.errors')
                    <form id="settingsValidation" method="post" action="{{ route('general.store') }}" class="form-horizontal" role="form"  enctype="multipart/form-data">
                        @csrf
                        @if(count($elements))                           
                            @foreach($elements as $section => $fields)
                                <div class="card">
                                 <h5 class="card-header bg-light">
                                    <label class="card-title">{{ $fields['title'] }} </label>
                                </h5>
                
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">{{  $fields['desc'] }}</h6>

                                  <div class="row">
                                        <div class="col-md-7 offset-md-1">
                                            @foreach($fields['elements'] as $field)
                                                @includeIf('form.' . $field['type'] )
                                            @endforeach
                                        </div> 
                                    </div> 
                                </div>
                            </div>                                 
                            @endforeach

                        @endif
                        <div class="row m-b-md">
                            <div class="col-md-12">
                                <button class="btn-primary btn pull-right">
                                    {{ __('application.Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>              
        </div>
    </div>
</div>
@endsection