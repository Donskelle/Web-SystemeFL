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

<?php
$publicDokus = [];
array_push($publicDokus, [
    "ID" => 1,
    "Name" => "sipgate",
    "NameShow" => "SipGate",
    "GroupName" => "entwicklung",
    "GroupNameShow" => "Entwicklung",
]);
array_push($publicDokus, [
    "ID" => 2,
    "Name" => "mummy",
    "NameShow" => "Mummy",
    "GroupName" => "entwicklung",
    "GroupNameShow" => "Entwicklung",
]);
array_push($publicDokus, [
    "ID" => 3,
    "Name" => "web",
    "NameShow" => "Web",
    "GroupName" => "entwicklung",
    "GroupNameShow" => "Entwicklung",
]);
array_push($publicDokus, [
    "ID" => 4,
    "Name" => "laravel",
    "NameShow" => "Laravel",
    "GroupName" => "verwaltung",
    "GroupNameShow" => "Verwaltung",
]);
$lastGroup = "";
foreach ($publicDokus as $value) {
    $Name = $value["Name"];
    $NameShow = $value["NameShow"];
    $GroupName = $value["GroupName"];
    $GroupNameShow = $value["GroupNameShow"];
    $AktivString = "";
    if ($lastGroup === "") {
        if ($DokuAccess === "public" && $DokuGroupAktive === $GroupName)
            $AktivString = 'class="active"';       
        $ul = <<<ul
<li $AktivString class="treeview">
    <a href="/dokumete/public/$GroupName"><span>$GroupNameShow</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
ul;
            echo $ul;
    }
    else {
        if ($lastGroup !== $GroupName) {
            if ($DokuAccess === "public" && $DokuGroupAktive === $GroupName)
                $AktivString = 'class="active"';
            $ul = <<<ul
    </ul>
</li>
<li $AktivString class="treeview">
    <a href="/dokumete/public/$GroupName"><span>$GroupNameShow</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
ul;
            echo $ul;
        }
    }
    $lastGroup = $GroupName;
    $li = <<<li
<li><a href="/dokumete/public/$GroupName/$Name">$NameShow</a></li>
li;
    $liAktiv = <<<liAktiv
<li class="active"><a href="/dokumete/public/$GroupName/$Name">$NameShow</a></li>
liAktiv;
    if ($Name === $DokuAktive && $DokuAccess === "public")
        echo $liAktiv;
    else
        echo $li;
}
?>
@endsection