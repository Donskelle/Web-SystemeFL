@section('privateNav')
<?php
if (!isset($DokuAccess))
    $DokuAccess = "";
if (!isset($DokuAktive))
    $DokuAktive = "";
?>
<li class="header">Home</li>
@if($DokuAccess === "")
<li class="active"><a href="/home"><span>Startseite</span></a></li>	
@else
<li><a href="/home"><span>Startseite</span></a></li>	
@endif
<li class="header">Meine Dokumente</li>
<!-- Optionally, you can add icons to the links -->
@if($DokuAccess === "new")
<li class="active"><a href="/dokumete/newDoku"><span>Neues Dokument</span></a></li>
@else
<li><a href="/dokumete/new"><span>Neues Dokument</span></a></li>
@endif
@if($DokuAccess==="private")
<li class="active" class="treeview">
    @else     
<li class="treeview">
    @endif  
    <a  href="/dokumete/private"><span>Dokumente</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @foreach ($privateDokus as $value)
        @if ($value["Name"] === $DokuAktive && $DokuAccess === "private")
        <li class="active"><a href="/dokumete/private/{{$value["Name"]}}">{{$value["NameShow"]}}</a></li>
        @else
        <li><a href="/dokumete/private/{{$value["Name"]}}">{{$value["NameShow"]}}</a></li>
        @endif
        @endforeach
    </ul>
</li>
@endsection
