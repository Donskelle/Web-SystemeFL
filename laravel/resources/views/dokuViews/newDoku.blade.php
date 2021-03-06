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
                    <form class="form-horizontal" role="form" method="POST" action="/document/create">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                            <label class="col-md-4 control-label">Layout</label>
                            <div class="col-md-6">
                                <select class="form-control" name="layout">
                                    <option value="default">Layout1</option>    
                                    <option value="sphinxdoc">Layout2</option>    
                                    <option value="agogo">Layout3</option>    
                                    <option value="sphinx_rtd_theme">Layout4</option> 
                                    <option value="scrolls">Layout5</option>
                                </select>                               
                            </div>
                        </div>                       
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    news Dokument anlegen 
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