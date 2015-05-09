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
                @foreach ($allUser as $user)  
                <tr>
                    <td>  <a class="users-list-name" href="/settings/profile/{{$user->username}}">edit</a></td>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td><img src="  {{ asset('/img/'.$user->imagePath) }}"  class="img-circle" alt="User Image" width="42" height="42"></td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->extra}}</td>
                    <td>10</td>
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
                @foreach ($allUser as $user)  
                <tr>
                    <td>  <a class="users-list-name" href="/settings/group/{{$user->username}}">edit</a></td>
                    <td>{{$user->id}}</td>
                   <td>{{$user->name}}</td>
                    <td>{{$user->extra}}</td>
                    <td>10</td>   
                     <td>20</td>   
                    @if($user->active ==1)
                    <td><span class="label label-success">aktiv</span></td>
                    @else
                    <td><span class="label label-danger">nicht aktiv</span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>                 
    </div><!-- /.box-body -->  
    <div class="box-footer clearfix">
                 
    </div>
</div>
@endsection