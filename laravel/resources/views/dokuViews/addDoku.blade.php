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
                <div class="panel-heading">Hinzufügen Einstellungen</div>
                <div class="panel-body">                   
                    <form class="form-horizontal" role="form" method="POST" action="/document/add">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $document->id }}">
                          <input type="hidden" name="lastURL" value="{{ \URL::previous() }}">
                         <input type="hidden" name="pathDocu" value="{{ $document->path }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">Dateiname</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="path" value="">
                            </div>
                        </div>                            
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    neuer Abschnitt erstellen 
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