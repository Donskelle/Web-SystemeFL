@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')


@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Benutzer Einstellungen</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body pad">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th> </th>
                    <th>User ID</th>
                    <th>Benuter Name</th>
                    <th>Benuter Bild</th>
                    <th>Name</th>
                    <th>Zusatz Info</th>
                    <th>Dokumenten Anzahl</th>
                    <th>Rechte</th>
                    <th>Aktiv</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allUsers as $user)
                <tr>
                    <td>  <a class="users-list-name" href="/settings/profile/{{$user->username}}"><i class="fa fa-pencil"></i></a></td>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td><img src="  {{ asset('/img/'.$user->imagePath) }}"  class="img-circle" alt="User Image" width="42" height="42"></td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->extra}}</td>
                    <td>{{count($user->documents)}}</td>
                    @if($user->permission == "0")
                    <td><span class="label label-danger">System Admin</span></td>
                    @elseif($user->permission == "1")
                    <td><span class="label label-success">Admin</span></td>
                    @else
                    <td><span class="label label-info">User</span></td>
                    @endif

                    @if($user->active =="1")
                    <td><span class="label label-success">aktiv</span></td>
                    @else
                    <td><span class="label label-danger">nicht aktiv</span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>


<div class="box">
    <div class="box-header">
        <h3 class="box-title">Gruppen Einstellungen</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body pad">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> </th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Info</th>
                    <th>Benutzer Anzahl</th>
                    <th>Dokumenten Anzahl</th>
                    <th>Aktiv</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allGroups as $group)
                <tr>
                    <td><a class="users-list-name" href="/settings/group/{{$group->id}}"><i class="fa fa-pencil"></i></a></td>
                    <td>{{$group->id}}</td>
                    <td>{{$group->name}}</td>
                    <td>{{$group->description}}</td>
                    <td>{{count($group->users)}}</td>
                    <td>{{count($group->documents)}}</td>
                    @if($group->active =="1")
                    <td><span class="label label-success">aktiv</span></td>
                    @else
                    <td><span class="label label-danger">nicht aktiv</span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    @if(\Auth::user()->permission < 1)
    <div class="box-footer clearfix">
        <form class="form-horizontal" role="form" method="POST" action="/settings/admin/addgroup">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <label class="col-md-3 control-label">Name der neue Gruppe</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="name" value="">
            </div>
            <button type="submit" class="col-md-3 btn btn-primary">Neue Gruppe anleggen </button>
        </form>
    </div>
    @endif
</div>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Dokumenten Einstellungen</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body pad">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> </th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Dateiname </th>
                    <th>Layout</th>
                    <th>Autor</th> 
                    <th>Gruppe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allDocuments as $document)
                @if(((!$document->group ) && \Auth::user()->permission < 1) ||($document->group ))
                <tr>
                    <td><a class="users-list-name" href="/settings/document/{{$document->id}}"><i class="fa fa-pencil"></i></a></td>
                    <td>{{$document->id}}</td>
                    <td>{{$document->name}}</td>
                    <td>{{$document->path}}</td>
                    <td>{{$document->layout}}</td>
                    <td>{{$document->user->name}} ({{$document->user->extra}} )</td>
                    @if($document->group)
                    <td>{{$document->group->group->name}}</td>
                    @else
                    <td>Private</td>
                    @endif
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->   
</div>
@endsection