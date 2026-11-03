<?php

include "database.php";

$name = "SportsHub Admin";
$email = "admin@sportshub.com";
$password = "admin123";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users
    (full_name, email, password, role)
    VALUES (?, ?, ?, 'admin')"
);

$stmt->bind_param(
    "sss",
    $name,
    $email,
    $hashed_password
);

if ($stmt->execute()) {

    echo "Admin created successfully.";

} else {

    echo "Error: " . $conn->error;

}

?>