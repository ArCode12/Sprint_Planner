<?php 
    include "db_connect.php"; 
    mysqli_select_db($conn, "sprint_planner"); 

    $data = json_decode(file_get_contents("php://input"), true); 

     $title = mysqli_real_escape_string($conn, $data["title"]);
  $column_name = mysqli_real_escape_string($conn, $data["column_name"]);
  $color = mysqli_real_escape_string($conn, $data["color"]);
  $priority = mysqli_real_escape_string($conn, $data["priority"]);

  $due_date = $data["due_date"];
  $due_date_value = $due_date === null ? "NULL" : "'" . mysqli_real_escape_string($conn, $due_date) . "'";

  $sql = "INSERT INTO cards (title, column_name, color, due_date, priority)
          VALUES ('$title', '$column_name', '$color', $due_date_value, '$priority')";
          
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true]); 
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]); 
    }
?> 