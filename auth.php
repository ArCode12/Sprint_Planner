<?php
  session_start();
  include "db_connect.php";
  mysqli_select_db($conn, "sprint_planner");

  $data = json_decode(file_get_contents("php://input"), true);
  $action = $data["action"] ?? "";

  if ($action === "signup") {
    $name = mysqli_real_escape_string($conn, $data["name"]);
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $password = password_hash($data["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "error" => "Email already in use"]);
    }
  }

  elseif ($action === "login") {
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($data["password"], $user["password"])) {
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user_name"] = $user["name"];
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "error" => "Incorrect email or password"]);
    }
  }

  elseif ($action === "logout") {
    session_destroy();
    echo json_encode(["success" => true]);
  }
?>