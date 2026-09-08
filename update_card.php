<?php
  session_start();
  include "db_connect.php";
  mysqli_select_db($conn, "sprint_planner");

  $user_id = $_SESSION["user_id"];

  $data = json_decode(file_get_contents("php://input"), true);
  $action = $data["action"] ?? "";
  $id = (int) $data["id"];

  if ($action === "delete") {
    $sql = "DELETE FROM cards WHERE id = $id AND user_id = $user_id";
  } elseif ($action === "move") {
    $column_name = mysqli_real_escape_string($conn, $data["column_name"]);
    $sql = "UPDATE cards SET column_name = '$column_name' WHERE id = $id AND user_id = $user_id";
  } else {
    $title = mysqli_real_escape_string($conn, $data["title"]);
    $color = mysqli_real_escape_string($conn, $data["color"]);
    $priority = mysqli_real_escape_string($conn, $data["priority"]);

    $due_date = $data["due_date"];
    $due_date_value = $due_date === null ? "NULL" : "'" . mysqli_real_escape_string($conn, $due_date) . "'";

    $sql = "UPDATE cards SET title = '$title', color = '$color', due_date = $due_date_value, priority = '$priority' WHERE id = $id AND user_id = $user_id";
  }

  if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
  }
?>