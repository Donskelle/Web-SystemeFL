@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')
@extends('dokuViews.aceEdit')

@section('content')
<meta name="_token" content="{{ csrf_token() }}"/>
<div class="box collapsed-box">
    <div class="box-header">
        <div class="col-md-12">
            <h3>Dokumenten Name: {{$document->name}}</h3>          
        </div>
        <div class="col-md-12">
            <a class="btn btn-app" href="/settings/document/{{$document->id}}">
                <i class="fa fa-edit"></i>Bearbeiten
            </a>
            <a class="btn btn-app" href="/document/add/{{$document->id}}">
                <i class="fa fa-plus-square"></i>Abschnitt hinzufügen
            </a>
            <a class="btn btn-app" onclick="location.reload();">
                <i class="fa fa-refresh"></i>Aktualisieren
            </a>

            <a class="btn btn-app" onclick="window.open('http://{{$_SERVER['SERVER_NAME']}}:8003/{{str_replace('/var/www/sphinx/','',$document->path)}}/build/html/','_blank');">
                <i class="fa fa-html5"></i>HTML 
            </a>
            <a class="btn btn-app" onclick="downloadPDF()">
                <i class="fa fa-file-pdf-o"></i>PDF
            </a>
        </div>
    </div> 			
</div>
@parent
@endsection


