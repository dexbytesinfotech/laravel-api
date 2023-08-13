@extends('layouts/app', ['activePage' => 'configuration', 'activeButton' => 'settings', 'title' => 'Configuration', 'navName' => 'Configuration'])
@php $indicesServer = \App\Dexlib\Software :: keys();  @endphp

@section('content')
<div class="content">
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    @include('alerts.success')
                    @include('alerts.errors')
                    
                    <form id="settingsValidation" method="post" action="{{ route('configuration.store') }}" class="form-horizontal" role="form">
                        @csrf
                        @if(count($elements))                            
                            @foreach($elements as $section => $fields)
                                <div class="card">
                                 <h5 class="card-header bg-light">
                                    <label class="card-title">{{ $fields['title'] }}</label>
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

        <div class="row mt-5">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <h5 class="card-header bg-light">
                        <label class="card-title">{{ __('application.Service informations') }}</label>
                    </h5>
                    <div class="card-body">
                          <h6 class="card-subtitle mb-2 text-muted">{{ __('application.Below you will find important informations required in some services!') }}</h6>

                      <table class="table">
                        <tr>
                            <th>{{ __('Domain') }}</th>
                            <td>{{ $_SERVER['HTTP_HOST'] }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('PHP Version') }}</th>
                            <td>{{ PHP_VERSION }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Current installation path') }}</th>
                            <tD>{{ base_path() }}</tD>
                        </tr>
                        <tr>
                            <th>{{ __('Public folder path') }}</th>
                            <td>{{public_path()}}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Storage folder path') }}</th>
                            <td>{{storage_path()}}</td>
                        </tr>

                         @foreach ($indicesServer as $key => $arg)
                            @if(isset($arg))
                            <tr>
                                <th>{{ ucwords(strtolower(str_replace('_', ' ', $arg))) }}</th>
                                <td>{{ $_SERVER[$arg] }}</td>
                            </tr>
                            @endif
                         @endforeach
                         
                      </table> 
                  </div>
              </div>                   
           </div>              
        </div>


    </div>
</div>
@endsection