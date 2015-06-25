@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')


@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Alle Änderungen und Ereignisse</h3>        
    </div><!-- /.box-header -->
    <div class="box-body pad">
        <table class="table table-striped">
            <thead>
                <tr>                    
                    <th>ID</th>
                    <th>Zeitpunkt</th>
                    <th>Gruppe</th>
                    <th>Dokument</th>
                    <th>Benuter</th>
                    <th>Rechte des Benuter</th>
                    <th>Art des Ereignisses </th>
                    <th>Zusatz Info</th>                  
                </tr>
            </thead>    
            <tbody>
                @foreach ($allNews as $news)
                <tr>
                    <td>{{$news->id}}</td>     
                    <td>{{$news->created_at}}</td> 

                    @if($news->group_id =='0')
                    <td>-</td>
                    @else
                    <td>{{$news->group->name}}</td> 
                    @endif

                    @if($news->document_id =='0')
                    <td>-</td>
                    @else
                    <td>{{$news->document->name}}</td> 
                    @endif


                    <td>{{$news->user->name}}</td>

                    @if($news->user->permission =='0')
                    <td>Admin</td>
                    @endif
                    @if($news->user->permission =='1')
                    <td>UserAdmin</td>
                    @endif
                    @if($news->user->permission =='2')
                    <td>User</td>
                    @endif


                    @if($news->mode =='1')
                    <td>Neue Benutzer Gruppen Projekte Dokumente</td>
                    @endif
                    @if($news->mode =='2')
                    <td>Bearbeiten der Benutzer Gruppen Projekte</td>
                    @endif
                    @if($news->mode =='3')
                    <td>Bearbeiten der Dokumente</td>
                    @endif             
                    <td>{{$news->description}}</td> 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>
@endsection