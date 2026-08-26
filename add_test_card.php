<?php 
    include "db_connect.php"; 

    mysqli_select_db($conn, "sprint_planner"); 

    $sql = "INSERT INTO cards (title, column_name, color)
            VALUES ('Draft the sprint goal', 'backlog', '#F6E58D')"; 

    if (mysqli_query($conn, $sql)) {
        echo "Card added successfully"; 
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
?> 