<?php 
    include "db_connect.php"; 

    mysqli_select_db($conn, "sprint_planner"); 

    $sql = "CREATE TABLE IF NOT EXISTS cards (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        title VARCHAR(120) NOT NULL, 
        column_name VARCHAR(50) NOT NULL, 
        color VARCHAR(20) DEFAULT '#F6E58D'
    )";

    if (mysqli_query($conn, $sql)) {
        echo "Table created successfully"; 
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
?> 