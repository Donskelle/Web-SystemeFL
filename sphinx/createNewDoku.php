<?php
//echo "Test";
$ProjectPath = "Test1";
$ProjectName = "Test1";
$ProjectTheme = "sphinx_rtd_theme";
$AuthorName = "Peter";
$shell_Befehl = "create ";
$shell_Befehl.= sprintf(" %s", $ProjectPath);    //Project name
$shell_Befehl.= sprintf(" %s", $ProjectName);    //Project name
$shell_Befehl.= sprintf(" %s", $AuthorName);     //Author name
$shell_Befehl.= sprintf(" %s", "V1.0");          //Version of project

$output = shell_exec("python " . $shell_Befehl);
echo "<pre>$output</pre>";
$output = shell_exec("sudo ./rechte.sh");
echo "<pre>$output</pre>";
$output = shell_exec("sudo perl -pi -e 's/default/sphinx_rtd_theme/g' /var/www/sphinx/Test1/source/conf.py");
echo "<pre>$output</pre>";
$output = shell_exec('ls -lart');
echo "<pre>$output</pre>";
?>