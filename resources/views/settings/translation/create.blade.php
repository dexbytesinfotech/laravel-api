@extends('layouts/app', ['activePage' => 'translation', 'activeButton' => 'settings', 'title' => 'Create a new language', 'navName' => 'Create a new language'])

@section('content')
<div class="content">
    <div class="container-fluid mt--6">
        <div class="row">     
            <div class="col-xl-12">
                <a href="{{ route('translation.index') }}" class="btn btn-default pull-right">
                  <span class="nc-icon nc-stre-left"></span> {{ __('application.Back') }}
                </a> 
            </div>
        </div>

         <div class="row">
            <div class="col-xl-12 order-xl-1">
                 @include('alerts.success')
                    @include('alerts.errors')

                    <div class="card">
                         <h5 class="card-header bg-light">
                            <label class="card-title">{{__('application.Create a new language')}}</label>
                        </h5>

                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6 offset-md-1">
                                 <form id="settingsValidation" method="post" action="{{ route('language.store') }}" class="form-horizontal" role="form">
                                    @csrf
                                       
                                <div class="form-group {{ $errors->has('language') ? ' has-error' : '' }}">
                                    <label class="form-control-label" for="language">
                                       {{__('application.Select a language')}}
                                    </label>
                                    <select name="language" 
                                            class="form-control" 
                                            id="language">
                                           @foreach($allLang as $val => $label)
                                            <option value="{{$val}}">{{$label}}</option>
                                           @endforeach
                                    </select>

                                    @if ($errors->has('language')) 
                                        <small class="help-block">
                                            {{ $errors->first('language') }}
                                        </small> 
                                    @endif
                                </div>

                                <div class="row m-b-md mt--6">
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
            </div>
        </div>
    </div>
</div>
@endsection