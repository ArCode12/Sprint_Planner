<?php 
    include "db_connect.php"; 
    
    $sql = "CREATE DATABASE IF NOT EXISTS sprint_planner"; 

    if (mysqli_query($conn, $sql)) {
        echo "Database created successfully"; 
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
?> 