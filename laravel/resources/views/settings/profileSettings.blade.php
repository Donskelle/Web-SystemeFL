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
                <div class="panel-heading">Profile Einstellungen</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Ups!</strong>Es sind einige Angaben nicht korrekt<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{'/settings/profile/'. $userShow->username.'/save' }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ $userShow->name }}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Zusatz Info</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="extra" value="{{ $userShow->extra }}">
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password Bestätigung</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation"> 
                            </div>
                        </div>

                        <!--                        <div class="form-group">
                                                    <label class="col-md-4 control-label">Profil Bild</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="imagePath" value="{{ $userShow->imagePath }}"> 
                                                    </div>
                                                </div>-->

                        @if(\Auth::user()->permission < 2 && \Auth::user()->username !==$userShow->username)
                        <div class="form-group">
                            <label class="col-md-4 control-label">Berechtigung</label>
                            <div class="col-md-6">                              
                                <select class="form-control" name="permission"> 
                                    @if($userShow->permission === "1")
                                    <option selected value=1>User Admin</option>
                                    <option value=2>User</option> 
                                    @else
                                    <option value=1>User Admin</option>
                                    <option selected value=2>User</option> 
                                    @endif                                    
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(\Auth::user()->permission <1 && \Auth::user()->username !==$userShow->username)
                        <div class="form-group">
                            <label class="col-md-4 control-label">Aktiver Nutzer</label>
                            <div class="col-md-6">                                   
                                <select class="form-control" name="active">
                                    @if($userShow->active === "1")
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
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Änderung Speicheren 
                                </button>
                            </div>
                        </div>
                    </form>



                </div>
                <div class="panel-heading">Profile Bild Einstellungen</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{'/settings/profile/'. $userShow->username.'/fileupload' }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Aktuelles Bild</label>
                            <div class="col-md-6">  
                                <img src="{{ asset('/img/'.$userShow->imagePath) }}" class="img-circle" alt="User Image" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Neues Bild</label>
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