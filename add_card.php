<?php 
    include "db_connect.php"; 
    mysqli_select_db($conn, "sprint_planner"); 

    $data = json_decode(file_get_contents("php://input"), true); 

    $title = mysqli_real_escape_string($conn, $data["title"]); 
    $column_name = mysqli_real_escape_string($conn, $data["column_name"]); 
    $color = mysqli_real_escape_string($conn, $data["color"]); 

    $sql = "INSERT INTO cards (title, column_name, color) 
            VALUES ('$title', '$column_name', '$color')";   

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true]); 
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]); 
    }
?> 