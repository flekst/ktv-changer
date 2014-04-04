<!DOCTYPE html> <html lang="ru"> <head> <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>
<body>

<?php
$cdate = date ("Сейчас Y/m/d, H:i:s");
$time_data =  "<div id='timestamp' style='float:right; padding:2pt; background:cyan;'>${cdate}</div>";
echo $time_data;
include('header.html');

$result = "";
if ( isset ($_REQUEST['input'])  ){
	// $input = $_REQUEST['input'];
	$result = require("action.php");
}

if ($init_hint) {
$hint_data = "<div id='log' style='float:left; top:0px; padding:3pt; margin:3pt; background:cyan;'>Выполнены замены:<ol>".$init_hint."</ol></div>";
} else {
$hint_data="";
}
?><?php echo $hint_data;?>
<div ><form method="POST"><textarea name="input" rows = "20" cols="80" ><?=htmlspecialchars($result);?></textarea><br />
<input type="submit" value="Обработать">
</form>
</div>

</body>
