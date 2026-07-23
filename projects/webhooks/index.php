<?php
header('Content-Type: application/json');
$headers = getallheaders();
if($headers["token"] == '181SUdnacirema1013152') {
$output = shell_exec("php ./test.php");
} else {
	$output = "Invalid access";
}
echo $output;
?>
