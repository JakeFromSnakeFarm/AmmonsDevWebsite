<?php
$name = $_GET['name'];
$email = $_GET['sender'];
$botEmail = $_GET['email'];
$phonen = $_GET['phonen'];
$street = $_GET['street'];
$city = $_GET['city'];
$zip = $_GET['zip'];
$desc = $_GET['desc'];
$service = $_GET['service'];
$anti = $_GET['anti'];
$isComplete = true;
switch ($service) {
    case 1:
        $service = "Home Inspection";
        break;
    case 2:
        $service = "4 Point Inspection";
        break;
    case 3:
        $service = "Wind Mitigation";
        break;
    case 4:
        $service = "None";
        break;
    default:
        $isComplete = false;
}
if($botEmail != "") {
    $isComplete = false;
}
if($service != "None") {
    if(strlen($zip) != 5) {
    $isComplete = false;
    }
}
if($anti != "001133") {
    $isComplete = false;
}
if($isComplete) {
    $msg = "$name\n$email\n$phonen\n$street $city $zip\nService Requested: $service\nDescription: $desc";
    echo "$msg";
    mail("jammons1@gmail.com", "Customer Inquiry",$msg."\nFrom: $email");
    mail("seeitallhomeinspection@gmail.com", "Customer Inquiry",$msg."\nFrom: $email");
} else {
    echo "Not complete form";
}
header("LOCATION: https://seeitallinspection.com");
?>