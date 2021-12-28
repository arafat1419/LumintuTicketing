<?php
    $host = "192.168.18.11";
    $user = "pintar_dev";
    $pass = "pintar123";
    $name = "lumintu-ticket";

    $conn = mysqli_connect($host, $user, $pass, $name);

    if (mysqli_connect_errno()){
        echo "Failed to connect to DB. " . mysqli_connect_error();
    }
?>