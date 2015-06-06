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
                            <div class="col-md-9 form-group2">
                                <label class="control-label label2">Benutzer (Zusatzinfo)</label>  
                                <label class="control-label">Zugewiesen</label> 
                            </div> 
                        </div> 
                        <hr>
                        @foreach ($allUsers as $user)
                        @if ($user->permission > 0)
                        <div class="form-group">
                            <div class="col-md-9 form-group2"> 
                                <input type="hidden" name="userID-{{$user->id}}" value="{{$user->id}}">
                                <div  class="iphone-toggle-buttons">
                                    <label  class="control-label label2">{{$user->name}} ({{$user->extra}})</label>                                
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
                        @endif
                        @endforeach
                    </div>
                    <div class="panel-heading">Verwalten der Dokumente in der Gruppe {{$group->name}}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-9 form-group2">
                                <label class="control-label label2">Dokument (Gruppe)</label>  
                                <label class="control-label">Zugewiesen</label> 
                            </div> 
                        </div> 
                        <hr>
                        @foreach ($allDocuments as $document)
                        <?php
                        $userInGroup = false;
                        foreach (\Auth::user()->groups as $singleGroupFromUser) {
                            if (is_object($document->group)) {
                                if ($singleGroupFromUser->group_id == $document->group->group_id) {
                                    $userInGroup = true;
                                }
                            } else {
                                $userInGroup = true;
                            }
                        }
                        ?>
                        @if($userInGroup ||\Auth::user()->permission == "0")
                        <div class="form-group">
                            <div class="col-md-9 form-group2"> 
                                <input type="hidden" name="documentID-{{$document->id}}" value="{{$document->id}}">   
                                <div class="iphone-toggle-buttons">            

                                    @if(is_object($document->group))  
                                    @if($document->group->group_id > 0)    
                                    <label class="control-label label2">{{$document->name}} ({{$document->group->group->name}} )</label>
                                    @if($document->group->group_id == $group->id)
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}" checked="checked"/><span></span></label>
                                    @else
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}"/><span></span></label>
                                    @endif
                                    @else
                                    <label class="control-label label2">{{$document->name}} (hat keine Gruppe)</label>
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}"/><span></span></label>

                                    @endif
                                    @else
                                    <label class="control-label label2">{{$document->name}} (hat keine Gruppe)</label>
                                    <label for="documentcheckbox-{{$document->id}}"><input type="checkbox" name="documentcheckbox-{{$document->id}}" id="documentcheckbox-{{$document->id}}"/><span></span></label>
                                    @endif
                                </div>
                            </div> 
                        </div>
                        @endif
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
                                <div class="col-md-1"></div>  
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Änderung Speicheren 
                                    </button>  
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <a href="/settings/admin" class="btn btn-primary">
                                        Zurück zur Übersicht
                                    </a>   
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
@endsection