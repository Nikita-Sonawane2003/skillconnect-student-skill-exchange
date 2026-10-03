<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$user_id = $_SESSION["user_id"];

/* Accept or reject request */

if (isset($_GET["action"]) && isset($_GET["id"])) {

    $connection_id = $_GET["id"];
    $action = $_GET["action"];

    if ($action == "accept") {

        $stmt = $conn->prepare("
            UPDATE connections
            SET status = 'accepted'
            WHERE id = ?
            AND receiver_id = ?
        ");

        $stmt->bind_param(
            "ii",
            $connection_id,
            $user_id
        );

        $stmt->execute();

    } elseif ($action == "reject") {

        $stmt = $conn->prepare("
            UPDATE connections
            SET status = 'rejected'
            WHERE id = ?
            AND receiver_id = ?
        ");

        $stmt->bind_param(
            "ii",
            $connection_id,
            $user_id
        );

        $stmt->execute();
    }

    header("Location: connections.php");
    exit();
}


/* Incoming requests */

$incoming = $conn->prepare("
    SELECT
        c.id,
        u.name,
        u.college
    FROM connections c
    JOIN users u
        ON c.sender_id = u.id
    WHERE c.receiver_id = ?
    AND c.status = 'pending'
    ORDER BY c.created_at DESC
");

$incoming->bind_param(
    "i",
    $user_id
);

$incoming->execute();

$incoming_result = $incoming->get_result();


/* Accepted connections */

$accepted = $conn->prepare("
    SELECT
        c.id,
        u.name,
        u.college
    FROM connections c
    JOIN users u
        ON (
            CASE
                WHEN c.sender_id = ? THEN c.receiver_id
                ELSE c.sender_id
            END
        ) = u.id
    WHERE
        (c.sender_id = ? OR c.receiver_id = ?)
        AND c.status = 'accepted'
    ORDER BY c.created_at DESC
");

$accepted->bind_param(
    "iii",
    $user_id,
    $user_id,
    $user_id
);

$accepted->execute();

$accepted_result = $accepted->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Connections - SkillConnect</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f7fb;
    color: #1f2937;
}

nav {
    background: #123c69;
    padding: 18px 8%;

    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: white;
    font-size: 25px;
    font-weight: bold;
}

nav a {
    color: white;
    text-decoration: none;
    margin-left: 22px;
}

.container {
    padding: 45px 8%;
}

.heading {
    margin-bottom: 35px;
}

.heading h1 {
    color: #123c69;
    margin-bottom: 10px;
}

.heading p {
    color: #64748b;
    font-size: 17px;
}

.section {
    margin-bottom: 45px;
}

.section h2 {
    color: #123c69;
    margin-bottom: 20px;
}

.cards {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(280px, 1fr));

    gap: 25px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 16px;

    box-shadow:
        0 10px 30px rgba(0,0,0,0.06);
}

.avatar {
    width: 55px;
    height: 55px;

    border-radius: 50%;

    background: #eef5ff;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 24px;

    margin-bottom: 15px;
}

.card h3 {
    color: #123c69;
    margin-bottom: 8px;
}

.college {
    color: #64748b;
    margin-bottom: 20px;
}

.buttons {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 18px;
    border-radius: 8px;

    text-decoration: none;

    font-weight: bold;
}

.accept {
    background: #2f80ed;
    color: white;
}

.reject {
    background: #f1f5f9;
    color: #475569;
}

.connected {
    background: #eef5ff;
    color: #123c69;

    padding: 10px 15px;

    border-radius: 20px;

    display: inline-block;

    font-weight: bold;
}

.empty {
    background: white;

    padding: 30px;

    border-radius: 15px;

    color: #64748b;
}

@media(max-width: 800px) {

    nav {
        flex-direction: column;
        gap: 15px;
    }

    nav a {
        margin: 0 8px;
    }

}

</style>

</head>

<body>

<nav>

    <div class="logo">
        SkillConnect
    </div>

    <div>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="skills.php">
            My Skills
        </a>

        <a href="matches.php">
            Find Partners
        </a>

        <a href="connections.php">
            Connections
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>


<div class="container">

    <div class="heading">

        <h1>
            My Connections 🤝
        </h1>

        <p>
            Manage your skill-sharing connections.
        </p>

    </div>


    <!-- Incoming Requests -->

    <div class="section">

        <h2>
            Connection Requests
        </h2>

        <?php if ($incoming_result->num_rows > 0) { ?>

            <div class="cards">

                <?php while ($request = $incoming_result->fetch_assoc()) { ?>

                    <div class="card">

                        <div class="avatar">
                            👨‍🎓
                        </div>

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $request["name"]
                            );
                            ?>
                        </h3>

                        <p class="college">
                            <?php
                            echo htmlspecialchars(
                                $request["college"]
                            );
                            ?>
                        </p>

                        <div class="buttons">

                            <a
                                href="connections.php?action=accept&id=<?php echo $request['id']; ?>"
                                class="btn accept"
                            >
                                Accept
                            </a>

                            <a
                                href="connections.php?action=reject&id=<?php echo $request['id']; ?>"
                                class="btn reject"
                            >
                                Reject
                            </a>

                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="empty">

                No pending connection requests.

            </div>

        <?php } ?>

    </div>


    <!-- Accepted Connections -->

    <div class="section">

        <h2>
            My Connected Students
        </h2>

        <?php if ($accepted_result->num_rows > 0) { ?>

            <div class="cards">

                <?php while ($connection = $accepted_result->fetch_assoc()) { ?>

                    <div class="card">

                        <div class="avatar">
                            🤝
                        </div>

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $connection["name"]
                            );
                            ?>
                        </h3>

                        <p class="college">
                            <?php
                            echo htmlspecialchars(
                                $connection["college"]
                            );
                            ?>
                        </p>

                        <span class="connected">
                            Connected ✓
                        </span>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="empty">

                You don't have any accepted connections yet.

            </div>

        <?php } ?>

    </div>

</div>

</body>

</html>
