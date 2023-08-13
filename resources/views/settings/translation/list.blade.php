@extends('layouts/app', ['activePage' => 'translation', 'activeButton' => 'settings', 'title' => __('Translation'), 'navName' => __(' :name Translation', ['name' => $langName])])

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
                            <label class="card-title">{{__('application.Edit the language :name', ['name' => $langName])}}</label>
                        </h5>

                        <div class="card-body">
                               <div class="row mt-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="directory">
                                            {{ __('application.Select a directory') }}
                                        </label>
                                        <select name="file" 
                                                class="form-control" 
                                                id="directory_list">
                                                <option value="">-</option>
                                                @foreach($directories as $val => $file)
                                                    <option value="{{ $file['code']}}" @if($file['code'] == $dirName ) selected  @endif >
                                                        {{ $file['name'] }}
                                                    </option>
                                                @endforeach
                                        </select>
                                    </div>                                
                                </div>

                                <div class="row mt-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="file">
                                            {{ __('application.Select a file') }}
                                        </label>
                                        <select name="file" 
                                                class="form-control" 
                                                id="file_list">
                                                <option value="">-</option>
                                                @foreach($files as $val => $file)
                                                    <option value="{{ $file['code']}}" @if($file['code'] == $fileName ) selected  @endif >
                                                        {{ $file['name'] }}
                                                    </option>
                                                @endforeach
                                        </select>
                                    </div>                                
                                </div>
                       </div>
                   </div>
            </div>
        </div>
    @if(!empty($contents))  

        
        <div class="row">     
            <div class="col-xl-12">
                <button class="btn btn-primary pull-right" id="translateMissing">
                  {{ __('application.Translate Missing') }}
                </button>
            </div>
        </div>

        <form id="settingsValidation" method="post" action="{{ route('translation.save') }}" class="form-horizontal" role="form">
         @csrf
        <input type="hidden" name="fileName" value="{{$fileName}}">
        <input type="hidden" name="lang" id="lang" value="{{$lang}}">
        <input type="hidden" name="dirName" id="dir" value="{{$dirName}}"> 

        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-hover">
                                <thead>
                                     <tr>
                                        <th scope="col" width="40%">{{ __('application.Default translation') }}</th>
                                        <th scope="col" width="60%">{{ __('application.Translation') }}
                                       <i class="small">
                                       ({{ __('application.leave blank to use default translation') }})</i>
                                    </th>
                                    </tr>
                                </thead>
                                 <tbody>
                                    @if(is_array($contents))
                                     @foreach($contents as $key => $value)
                                        <tr>
                                            <td class="wrap">{{ $value['original'] }}</td>
                                            <td>
                                                <input class="form-control" type="text" id="translation-input-{{$value['id']}}" data-original="{{ $value['original'] }}" data-id="{{ $value['id'] }}"  name="translationValues[{{$key}}]" value="{{ $value['translate'] }}">
                                            </td>
                                        </tr>
                                    
                                    @endforeach
                                    @endif
                                 </tbody>                       
                            </table>                                    
                        </div>
                   </div>
               </div>
            </div>
            </div>

            <div class="row m-b-md">
                <div class="col-md-12">
                    <button class="btn-primary btn pull-right">
                        {{ __('application.Save') }}
                    </button>
                </div>
            </div>
        </form>

    @endif
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
<script type="text/javascript">
    $(document).ready(function () {   
        $('#file_list').on('change', function () {
          var id = $(this).val();
          if (id) { 
            var URL = "{{ route('translation.edit', ['lang' => $lang, 'file' => 'FILENAME', 'dir' => 'DIRNAME']) }}";
            URL = URL.replace('FILENAME', id);
            URL = URL.replace('DIRNAME', $('#directory_list').val());
            window.location.replace(URL);
          }
          return false;
        });


        $('#directory_list').on('change', function () {
          var id = $(this).val();
          if (id) { 
            var URL = "{{ route('translation.edit', ['lang' => $lang, 'file' => 'FILENAME', 'dir' => 'DIRNAME']) }}";
            URL = URL.replace('FILENAME', '');
            URL = URL.replace('DIRNAME', id);
            window.location.replace(URL);
          }
          return false;
        });


    }); 
    
    
    
    $(document).on('click','#translateMissing',function(e) {
        var isSuccess = true;
        $('input:text').filter(function() { 
            if($(this).val() == "" && isSuccess){
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ URL::route('translation.translate') }}/',
                    data : { original : $(this).data('original'), lang : $('#lang').val(), 'id': $(this).attr('id')},
                    dataType: 'json',
                    success: function( data ) { 
                        $('#'+data.id).val(data.text);  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        isSuccess = false;
                     //   alert(textStatus + ' - '+ jqXHR.statusText);
                       
                    }
                }); 
            }; 
        });
    }); 
 </script>
 