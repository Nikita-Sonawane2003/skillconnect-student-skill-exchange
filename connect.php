<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$sender_id = $_SESSION["user_id"];
$receiver_id = $_GET["user_id"] ?? 0;

if ($receiver_id == 0 || $receiver_id == $sender_id) {
    header("Location: matches.php");
    exit();
}

/* Check if a connection already exists */

$check = $conn->prepare("
    SELECT id
    FROM connections
    WHERE
        (sender_id = ? AND receiver_id = ?)
        OR
        (sender_id = ? AND receiver_id = ?)
");

$check->bind_param(
    "iiii",
    $sender_id,
    $receiver_id,
    $receiver_id,
    $sender_id
);

$check->execute();

$result = $check->get_result();

if ($result->num_rows == 0) {

    $insert = $conn->prepare("
        INSERT INTO connections
        (sender_id, receiver_id, status)
        VALUES (?, ?, 'pending')
    ");

    $insert->bind_param(
        "ii",
        $sender_id,
        $receiver_id
    );

    $insert->execute();
}

header("Location: connections.php");
exit();

?>
