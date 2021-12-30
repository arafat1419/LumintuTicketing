<?php
    $host = "arisukarno.xyz";
    $user = "root";
    $pass = "Lumintu-ticket2021";
    $name = "lumintu_ticket_merge";

    $conn = mysqli_connect($host, $user, $pass, $name);

    if (mysqli_connect_errno()){
        echo "Failed to connect to DB. " . mysqli_connect_error();
    } else {
        echo "Success connect to DB";
    }
?>