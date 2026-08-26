<?php 
    include "db_connect.php"; 

    mysqli_select_db($conn, "sprint_planner"); 

    $sql = "SELECT * FROM cards"; 
    $result = mysqli_query($conn, $sql); 

    $cards = []; 

    while ($row = mysqli_fetch_assoc($result)) {
        $cards[] = $row; 
    }

    header("Content-Type: application/json"); 
    echo json_encode($cards); 
?> 