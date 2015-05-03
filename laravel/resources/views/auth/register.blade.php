@extends('app')
@extends('header.header')

@section('helpInfo')

<li class="header">
    Zum Registrien geben sie bitte folgende Daten ein
</li>
<li><a><i class="fa fa-circle-o"></i>Benutzer Name</a></li>
<li><a><i class="fa fa-circle-o"></i>Name</a></li>
<li><a><i class="fa fa-circle-o"></i>Zusatz Info</a></li>
<li><a><i class="fa fa-circle-o"></i>Password</a></li>
<li><a><i class="fa fa-circle-o"></i>Password Bestätigung</a></li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
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

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                         <div class="form-group">
                            <label class="col-md-4 control-label">Benutzer Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div> 
                         <div class="form-group">
                            <label class="col-md-4 control-label">Zusatz Info</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="extra" value="{{ old('extra') }}">
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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
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
