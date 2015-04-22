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
        <?php
        $privateDokus = [];
        array_push($privateDokus, [
            "ID" => 1,
            "Name" => "sipgate",
            "NameShow" => "SipGate"
        ]);
        array_push($privateDokus, [
            "ID" => 2,
            "Name" => "mummy",
            "NameShow" => "Mummy"
        ]);
        array_push($privateDokus, [
            "ID" => 3,
            "Name" => "laravel",
            "NameShow" => "Laravel"
        ]);
        array_push($privateDokus, [
            "ID" => 4,
            "Name" => "typo3",
            "NameShow" => "Typo3"
        ]);
        foreach ($privateDokus as $value) {
            $Name = $value["Name"];
            $NameShow = $value["NameShow"];
            $li = <<<li
<li><a href="/dokumete/private/$Name">$NameShow</a></li>
li;
            $liAktiv = <<<liAktiv
<li class="active"><a href="/dokumete/private/$Name">$NameShow</a></li>
liAktiv;
            if ($Name === $DokuAktive && $DokuAccess === "private")
                echo $liAktiv;
            else
                echo $li;
        }
        ?>
    </ul>
</li>
@endsection
