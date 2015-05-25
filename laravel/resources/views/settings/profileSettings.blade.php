@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')

@section('content')
@if(\Auth::user()->permission <= $userShow->permission)
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
                        @if(\Auth::user()->username ==$userShow->username)
                        <hr>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Browser Anzeige Einstellungen</label>                    
                            <input type="hidden" id="browser_layout" name="browser_layout" value="{{ $userShow->browser_layout }}">                         
                        </div>                        
                        <div class="form-group">
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='change_skin("skin-black");
                                        document.getElementById("browser_layout").value = "skin-black";' style='display: block; box-shadow: -1px 1px 2px rgba(0,0,0,0.0);' class='clearfix full-opacity-hover'>
                                    <div style='box-shadow: 0 0 2px rgba(0,0,0,0.1)' class='clearfix'><span style='display:block; width: 20%; float: left; height: 10px; background: #fefefe;'></span><span style='display:block; width: 80%; float: left; height: 10px; background: #fefefe;'></span></div>
                                    <div><span style='display:block; width: 20%; float: left; height: 40px; background: #222;'></span><span style='display:block; width: 80%; float: left; height: 40px; background: #f4f5f7;'></span></div>
                                    <p class='text-center'>Skin Black</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='change_skin("skin-green");
                                        document.getElementById("browser_layout").value = "skin-green";' style='display: block; box-shadow: -1px 1px 2px rgba(0,0,0,0.0);' class='clearfix full-opacity-hover'>
                                    <div><span style='display:block; width: 20%; float: left; height: 10px;' class='bg-green-active'></span><span class='bg-green' style='display:block; width: 80%; float: left; height: 10px;'></span></div>
                                    <div><span style='display:block; width: 20%; float: left; height: 40px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 40px; background: #f4f5f7;'></span></div>
                                    <p class='text-center'>Skin Green</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='change_skin("skin-red");
                                        document.getElementById("browser_layout").value = "skin-red";' style='display: block; box-shadow: -1px 1px 2px rgba(0,0,0,0.0);' class='clearfix full-opacity-hover'>
                                    <div><span style='display:block; width: 20%; float: left; height: 10px;' class='bg-red-active'></span><span class='bg-red' style='display:block; width: 80%; float: left; height: 10px;'></span></div>
                                    <div><span style='display:block; width: 20%; float: left; height: 40px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 40px; background: #f4f5f7;'></span></div>
                                    <p class='text-center'>Skin Red</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='change_skin("skin-yellow");
                                        document.getElementById("browser_layout").value = "skin-yellow";' style='display: block; box-shadow: -1px 1px 2px rgba(0,0,0,0.0);' class='clearfix full-opacity-hover'>
                                    <div><span style='display:block; width: 20%; float: left; height: 10px;' class='bg-yellow-active'></span><span class='bg-yellow' style='display:block; width: 80%; float: left; height: 10px;'></span></div>
                                    <div><span style='display:block; width: 20%; float: left; height: 40px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 40px; background: #f4f5f7;'></span></div>
                                    <p class='text-center'>Skin Yellow</p>
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Editor Layout</label>                        
                            <input type="hidden" id="editor_layout" name="editor_layout" value="{{ $userShow->editor_layout }}">
                        </div> 
                        <div class="form-group">
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='document.getElementById("editor_layout").value = "skin-black";'
                                   <p class='text-center'>Skin Black</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='document.getElementById("editor_layout").value = "skin-black";'
                                   <p class='text-center'>Skin Black</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='document.getElementById("editor_layout").value = "skin-black";'
                                   <p class='text-center'>Skin Black</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href='javascript:void(0);' onclick='document.getElementById("editor_layout").value = "skin-black";'
                                   <p class='text-center'>Skin Black</p>
                                </a>
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
                            <label class="col-md-4 control-label">Aktuelles Bild </br> Neues Bild (Format 160x160 Pixel +-40)</label>
                            <div class="col-md-6">  
                                <img src="{{ asset('/img/'.$userShow->imagePath) }}" class="img-circle" alt="User Image" />
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

@endif
@endsection