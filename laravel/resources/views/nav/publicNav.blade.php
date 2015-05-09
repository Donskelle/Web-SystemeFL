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

<?php $lastGroup = ""; 

?>
@foreach ($publicDokus as $value)
@if ($lastGroup !== $value["GroupName"])
    @if ($lastGroup !== "")
    </ul>
    </li>
    @endif

    @if ($DokuAccess === "public" && $DokuGroupAktive === $value["GroupName"])
    <li class="active" class="treeview">
        @else
         <li class="treeview">
    @endif
    <a href="/dokumete/public/{{$value["GroupName"]}}"><span>{{$value["GroupNameShow"]}}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
 @endif
<?php $lastGroup = $value["GroupName"]; ?>   


@if($value["Name"] === $DokuAktive && $DokuAccess === "public")
    <li class="active"><a href="/dokumete/public/{{$value["GroupName"]}}/{{$value["Name"]}}">{{$value["NameShow"]}}</a></li>
@else
    <li><a href="/dokumete/public/{{$value["GroupName"]}}/{{$value["Name"]}}">{{$value["NameShow"]}}</a></li>
@endif
@endforeach
    </ul>
</li>
@endsection