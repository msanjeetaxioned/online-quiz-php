<?php
if(isset($_COOKIE[SUBMIT])) {
    $submittedData = json_decode($_COOKIE[SUBMIT], true);
    $name = $submittedData['name'];
    $gender = $submittedData['gender'];
    $mobile = $submittedData['mobile'];
}

setcookie(SUBMIT, "", time() - 300, "/", "", 0);