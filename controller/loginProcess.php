<?php

    include('../config.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT `customer_password` FROM `customer` WHERE `customer_email` = '$email'";
    $getPassword = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $passData = $getPassword->fetch_array()[0];

    if (md5($password) == $passData){
        $verQuery = "SELECT `status` FROM `customer` WHERE `customer_email` = '$email'";
        $getStatus = mysqli_query($conn, $verQuery) or die(mysqli_error($conn));
        if ($getStatus->fetch_array()[0] == 'Verified'){
            header('Location: ../view/details.php');
        }else{
            header('Location: ../view/login.php?st');
        }
    }
    else{
        header('Location: ../view/login.php?wp');
    }
?>