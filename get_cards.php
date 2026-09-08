<?php 
    session_start(); 
    include "db_connect.php"; 

    mysqli_select_db($conn, "sprint_planner"); 

    $user_id = $_SESSION["user_id"]; 

    $sql = "SELECT * FROM cards WHERE user_id = $user_id"; 
    $result = mysqli_query($conn, $sql); 

    $cards = []; 

    while ($row = mysqli_fetch_assoc($result)) {
        $cards[] = $row; 
    }

    header("Content-Type: application/json"); 
    echo json_encode($cards); 
?> 