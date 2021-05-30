<?php
    include 'connection.php';

    $conn = connect();

    $sql = "select * from customer";
    $customers = $conn->query($sql);

    if($customers) {
        echo "Returned rows are: " . $customers -> num_rows;
    }

    $conn->close();
?>