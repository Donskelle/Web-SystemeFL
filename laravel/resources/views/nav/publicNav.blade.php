@section('publicNav')
<?php
if (!isset($docuAccess))
    $docuAccess = "";
if (!isset($docuGroupAktive))
    $docuGroupAktive = "";
if (!isset($docuAktive))
    $docuAktive = "";
?>
@if (\Auth::user()->permission == 0)
<li class="header">Alle Gruppen(Admin)</li>
@foreach (\App\Models\group::all() as $group)
@if ($docuAccess === "public" && $docuGroupAktive === $group->id)
<li class="active" class="treeview">
    @else
<li class="treeview">    
    @endif   
    <a href="/document/public/{{$group->id}}"><span>{{$group->name}}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @foreach($group->documents as $document)      
        @if($document->document_id === $docuAktive && $docuAccess === "public")
        <li class="active"><a href="/document/public/{{$group->id}}/{{$document->document_id}}">{{$document->document->name}}</a></li>
        @else
        <li><a href="/document/public/{{$group->id}}/{{$document->document_id}}">{{$document->document->name}}</a></li>
        @endif
        @endforeach   
    </ul>
</li>
@endforeach
@else           
<li class="header">Für mich freigegeben</li>
@foreach ($publicGroups as $group)
@if ($docuAccess === "public" && $docuGroupAktive === $group->group_id)
<li class="active" class="treeview">
    @else
<li class="treeview">    
    @endif   
    <a href="/document/public/{{$group->group_id}}"><span>{{$group->group->name}}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @foreach($group->group->documents as $document)      
        @if($document->document_id === $docuAktive && $docuAccess === "public")
        <li class="active"><a href="/document/public/{{$group->group_id}}/{{$document->document_id}}">{{$document->document->name}}</a></li>
        @else
        <li><a href="/document/public/{{$group->group_id}}/{{$document->document_id}}">{{$document->document->name}}</a></li>
        @endif
        @endforeach   
    </ul>
</li>
@endforeach
@endif
@endsection