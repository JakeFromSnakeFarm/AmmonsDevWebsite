<?php
$name = $_POST['name'];
$email = $_POST['sender'];
$botEmail = $_POST['email'];
$desc = $_POST['desc'];
$anti = $_POST['anti'];
$isComplete = true;

if($desc == "") {
    $isComplete = false;
}

if($botEmail != "") {
    $isComplete = false;
}

if($anti != "001133") {
    $isComplete = false;
}

if($isComplete) {
    $msg = "$name\n$email\nDescription: $desc";
    //echo "$msg";
    mail("jammons1@gmail.com", "Customer Inquiry",$msg."\nFrom: $email");
} else {
    //echo "Not complete form";
}
?>