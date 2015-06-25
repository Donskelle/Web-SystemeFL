@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')

@section('content') 

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dokument Einstellungen</div>
                <div class="panel-body">                   
                    <form class="form-horizontal" role="form" method="POST" action="{{'/settings/document/'.$document->id .'/save' }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                        <input type="hidden" name="lastURL" value="{{ \URL::previous() }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ $document->name }}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Dateiname</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="path" value="{{ $document->path }}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Layout</label>
                            <div class="col-md-6">
                                <select class="form-control" name="layout">
                                    <option value="default" 
                                            @if($document->layout =="default")
                                            selected 
                                            @endif
                                            >Layout1</option>    
                                    <option value="sphinxdoc" 
                                            @if($document->layout =="sphinxdoc")
                                            selected 
                                            @endif
                                            >Layout2</option>    
                                    <option value="agogo" 
                                            @if($document->layout =="agogo")
                                            selected 
                                            @endif
                                            >Layout3</option>    
                                    <option value="sphinx_rtd_theme" 
                                            @if($document->layout =="sphinx_rtd_theme")
                                            selected 
                                            @endif
                                            >Layout4</option>  
                                    <option value="scrolls" 
                                            @if($document->layout =="scrolls")
                                            selected 
                                            @endif
                                            >Layout5</option>   
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Autor</label>
                            <div class="col-md-6">                                
                                <input type="text" readonly="readonly" class="form-control" name="user_name" value="{{ $document->user->name }} ({{ $document->user->extra }})">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Gruppe</label>
                            <div class="col-md-6">  
                                <select class="form-control" name="group_id">
                                    <?php $documentInGroup = false; ?>
                                    @foreach ($allGroups as $group)
                                    <?php
                                    $userInGroup = false;
                                    foreach (\Auth::user()->groups as $singleGroupFromUser) {
                                        if ($singleGroupFromUser->group_id == $group->id) {
                                            $userInGroup = true;
                                        }
                                    }
                                    $documentSelected = false;
                                    if ($document->group) {
                                        if ($group->id == $document->group->group_id) {
                                            $documentSelected = true;
                                            $userInGroup = true;
                                            $documentInGroup = true;
                                        }
                                    }
                                    ?>
                                    @if($userInGroup ||\Auth::user()->permission == "0")
                                    @if($documentSelected)               
                                    <option selected value= {{$group->id}}>{{$group->name}}</option>        
                                    @else                                  
                                    <option value={{$group->id}}>{{$group->name}}</option>       
                                    @endif                                      
                                    @endif
                                    @endforeach
                                    @if(!$documentInGroup)
                                    <option selected value=0>nicht zugewiesen</option>  
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Änderung Speicheren 
                                </button>
                            </div>
                        </div>
                    </form>
                </div> 
                <div class="panel-heading">Dokumenten Bild Hochladen</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{'/settings/document/'.$document->id .'/fileupload' }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Aktuelle Bilder </br> Nur ein Bild zurzeit hochladen. </br>Zum einfügen </br>.. image:: _templates/X.png</label>
                            <div class="col-md-6">  
                                <ul>
                                    @foreach ($images as $image)
                                    <li>_templates/{{$image}}</li>
                                    @endforeach
                                    @if(count($images) ==0)
                                    <li>keine Bilder vorhanden</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">                          
                            <div class="col-md-6 col-md-offset-4">
                                <input  class="btn btn-primary" type="file" id="file" name="file" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Bild hochladen 
                                </button>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection