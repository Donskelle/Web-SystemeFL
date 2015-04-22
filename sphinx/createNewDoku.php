<?php
//echo "Test";
//$ProjectName = "Test";
//$AuthorName = "Peter";
//$shell_Befehl = "./createNewSphinxDoku ";
////$shell_Befehl.= sprintf(" --sep --dot=.");
////$shell_Befehl.= sprintf(" -p %s", $ProjectName);    //Project name
////$shell_Befehl.= sprintf(" -a %s", $AuthorName);     //Author name
////$shell_Befehl.= sprintf(" -v %s", "V1.0");          //Version of project
////$shell_Befehl.= sprintf(" -l %s", "de");            //Document language
////$shell_Befehl.= sprintf(" --suffix %s", ".rst");    //Source file suffix
////$shell_Befehl.= sprintf(" --master %s", "index");   //Master document name
////$shell_Befehl.= sprintf(" --epub");                 //Use epub

$output = shell_exec("./createNewSphinxDoku");
echo "<pre>$output</pre>";
$output = shell_exec("sudo ./changeAlleRechte");
echo "<pre>$output</pre>";
$output = shell_exec('ls -lart');
echo "<pre>$output</pre>";
?>