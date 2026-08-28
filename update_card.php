<?php
  include "db_connect.php";
  mysqli_select_db($conn, "sprint_planner");

  $data = json_decode(file_get_contents("php://input"), true);
  $action = $data["action"] ?? "";
  $id = (int) $data["id"];

  if ($action === "delete") {
    $sql = "DELETE FROM cards WHERE id = $id";
  } else {
    $title = mysqli_real_escape_string($conn, $data["title"]);
    $color = mysqli_real_escape_string($conn, $data["color"]);
    $sql = "UPDATE cards SET title = '$title', color = '$color' WHERE id = $id";
  }

  if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
  }
?>