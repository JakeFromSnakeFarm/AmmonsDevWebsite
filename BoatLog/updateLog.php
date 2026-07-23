<?php
$json = file_get_contents('php://input');
$data = json_encode($json);
echo $data ;
file_put_contents("log0.json", $json);
?>