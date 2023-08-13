@extends('layouts/app', ['activePage' => 'translation', 'activeButton' => 'settings', 'title' => 'Language', 'navName' => 'Language'])

@section('content')
<div class="content">
    <div class="container-fluid mt--6">

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <a href="{{ route('language.create') }}" class="btn btn-primary pull-right">
                  <span class="fa fa-plus"></span> {{__('application.Create a new language')}}
                </a> 
            </div>
        </div>

        <div class="row  mt-3">
            <div class="col-xl-12 order-xl-1">
                    <div class="card">
                         <h5 class="card-header bg-light">
                            <label class="card-title">{{__('application.List of your languages')}}</label>
                        </h5>

                        <div class="card-body">
                           <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <table class="table">
                                        <tr>
                                            <th>{{ __('application.Language')}}</th>
                                            <th class="text-center">{{ __('application.Action')}}</th>
                                        </tr>
                                    @foreach(\App\Dexlib\Locale::getActiveLang() as $key => $lang)
                                        <tr>
                                            <td>{{$lang}}</td>
                                            <td class="text-center">
                                                <a class="nav-link" href="{{ route('translation.edit', $key) }}">
                                                    {{ __('application.Edit') }} 
                                                </a>
                                            </td> 
                                        </tr>
                                    @endforeach
                                    </table>
                                </div> 
                            </div> 
                        </div>
                    </div>                              
             </div>
        </div>
    </div>
</div>
@endsection