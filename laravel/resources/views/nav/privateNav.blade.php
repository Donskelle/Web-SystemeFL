@section('privateNav')
<?php
if (!isset($docuAccess))
    $docuAccess = "";
if (!isset($docuAktive))
    $docuAktive = "";
?>
<li class="header">Home</li>
@if($docuAccess === "")
<li class="active"><a href="/home"><span>Startseite</span></a></li>	
@else
<li><a href="/home"><span>Startseite</span></a></li>	
@endif
<li class="header">Meine Dokumente</li>
<!-- Optionally, you can add icons to the links -->
@if($docuAccess === "new")
<li class="active"><a href="/document/newDocu"><span>Neues Dokument</span></a></li>
@else
<li><a href="/document/new"><span>Neues Dokument</span></a></li>
@endif
@if($docuAccess==="private")
<li class="active" class="treeview">
    @else     
<li class="treeview">
    @endif  
    <a  href="/document/private"><span>Dokumente</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @foreach ($privateDocus as $value)
        @if ($value->id === $docuAktive && $docuAccess === "private")
        <li class="active"><a href="/document/private/{{$value->id }}">{{$value->name}}</a></li>
        @else
        <li><a href="/document/private/{{$value->id }}">{{$value->name}}</a></li>
        @endif
        @endforeach
    </ul>
</li>
@endsection
