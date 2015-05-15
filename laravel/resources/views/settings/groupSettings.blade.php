@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')

@section('content')
<link href="{{ asset('/css/Radiobutton.css') }}" rel="stylesheet" type="text/css" />

<div class="container-fluid">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" method="POST" action="{{'/settings/group/'.$group->id.'/save' }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default">                
                    <div class="panel-heading">Verwalten der Benutzer in der Gruppe {{$group->name}}</div>
                    <div class="panel-body">    

                        <div class="form-group">
                            <label class="col-md-6 control-label">Benutzer (Zusatzinfo)</label>  
                            <label class="col-md-2 control-label">Zugewiesen</label> 
                        </div> 
                        @foreach ($allUsers as $user)
                        <div class="form-group">
                            <label class="col-md-6 control-label">{{$user->name}} </br>({{$user->extra}})</label>
                            <div class="col-md-4">
                                <input type="hidden" name="userID-{{$user->id}}" value="{{$user->id}}">
                                <div class="iphone-toggle-buttons">                                
                                    <?php $userIn = false; ?>
                                    @if(is_object($user->groups)) 
                                    @foreach ($user->groups as $singleGroup)
                                    @if($singleGroup->group_id ===$group->id)
                                    <?php $userIn = true; ?>
                                    @endif                             
                                    @endforeach 
                                    @endif

                                    @if($userIn)
                                    <label for="usercheckbox-{{$user->id}}"><input type="checkbox" name="usercheckbox-{{$user->id}}" id="usercheckbox-{{$user->id}}" checked="checked"/><span></span></label>
                                    @else
                                    <label for="usercheckbox-{{$user->id}}"><input type="checkbox" name="usercheckbox-{{$user->id}}" id="usercheckbox-{{$user->id}}"/><span></span></label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="panel-heading">Verwalten der Dokumente in der Gruppe {{$group->name}}</div>
                    <div class="panel-body">
                        @foreach ($allDocuments as $document)                     
                        <div class="form-group">
                            <label class="col-md-6 control-label">{{$document->name}}</label>
                            <div class="col-md-4">
                                <input type="hidden" name="documentID-{{$document->id}}" value="{{$document->id}}">
                                <div class="iphone-toggle-buttons">                              
                                    @if(is_object($document->group))     
                                    @if($document->group->group_id == $group->id)
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}" checked="checked"/><span></span></label>
                                    @else
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}"/><span></span></label>
                                    @endif
                                    @else
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}"/><span></span></label>
                                    @endif
                                </div>
                            </div> 
                        </div>
                        @endforeach 
                    </div>
                    <div class="panel panel-default">                
                        <div class="panel-heading">Verwalten der Extras in der Gruppe {{$group->name}}</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ $group->name }}">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label">Beschreibung</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="description" value="{{ $group->description }}">
                                </div>
                            </div> 

                            @if(\Auth::user()->permission <1)
                            <div class="form-group">
                                <label class="col-md-4 control-label">Aktive Gruppe</label>
                                <div class="col-md-6">                                   
                                    <select class="form-control" name="active">
                                        @if($group->active === "1")
                                        <option value=0>Nicht Aktiv</option>
                                        <option selected value=1>Aktiv</option>        
                                        @else
                                        <option selected value=0>Nicht Aktiv</option>
                                        <option value=1>Aktiv</option>       
                                        @endif      
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-5 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
                                        Änderung Speicheren 
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <a href="/settings/admin" class="btn btn-primary">
                                        Zurück zur Übersicht
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
@endsection