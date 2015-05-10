@section('publicNav')
<?php
if (!isset($DokuAccess))
    $DokuAccess = "";
if (!isset($DokuGroupAktive))
    $DokuGroupAktive = "";
if (!isset($DokuAktive))
    $DokuAktive = "";
?>
<li class="header">Für mich freigegeben</li>
@foreach ($publicGroups as $group)
@if ($DokuAccess === "public" && $DokuGroupAktive === $group->group_id)
<li class="active" class="treeview">
    @else
<li class="treeview">
    @endif
    <a href="/dokumete/public/{{$group->group_id}}"><span>{{$group->group->name}}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @foreach($group->group->documents as $document)
        @if($document->document_id === $DokuAktive && $DokuAccess === "public")
        <li class="active"><a href="/dokumete/public/{{$group->group_id}}/{{$document->document_id}}">{{$document->document->name}}</a></li>
        @else
        <li><a href="/dokumete/public/{{$group->document_id}}/{{$document->document_id}}">{{$document->document->name}}</a></li>
        @endif
        @endforeach   
    </ul>
</li>
@endforeach

@endsection