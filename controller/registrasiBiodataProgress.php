<?php
    require_once './config.php';

    $email = $_POST['email'];
    $name = $_POST['name'];
    $phoneNum = $_POST['phoneNum'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    if ($password != $repeatPassword){
        $error = "Make sure to match your password.";
        header("Location: ../view/registration/bioRegistration.php?msg=".$error);
    }else{
        $query = "INSERT INTO `customer` (`customer_email`, `customer_password`)";
        $stmt = $DB->prepare($query);
    }
?>