<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

/* Count connections */
$sql = "SELECT COUNT(*) AS total FROM connections
        WHERE (sender_id = ? OR receiver_id = ?)
        AND status = 'accepted'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$connection_data = $result->fetch_assoc();

$total_connections = $connection_data["total"];

$stmt->close();


/* Count skills */
$sql = "SELECT COUNT(*) AS total
        FROM user_skills
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$skill_data = $result->fetch_assoc();

$total_skills = $skill_data["total"];

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Dashboard - SkillConnect</title>

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

/* Navbar */

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

/* Main */

.container {
    padding: 45px 8%;
}

/* Welcome */

.welcome {
    margin-bottom: 35px;
}

.welcome h1 {
    color: #123c69;
    font-size: 34px;
    margin-bottom: 10px;
}

.welcome p {
    color: #64748b;
    font-size: 17px;
}

/* Stats */

.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 35px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.stat-card h3 {
    color: #64748b;
    font-size: 15px;
    margin-bottom: 10px;
}

.stat-card .number {
    font-size: 32px;
    font-weight: bold;
    color: #123c69;
}

/* Actions */

.section-title {
    color: #123c69;
    margin-bottom: 20px;
}

.actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.action-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.action-card h3 {
    color: #123c69;
    margin-bottom: 10px;
}

.action-card p {
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 20px;
}

.btn {
    display: inline-block;
    background: #2f80ed;
    color: white;
    padding: 11px 18px;
    border-radius: 7px;
    text-decoration: none;
    font-weight: bold;
}

.btn:hover {
    background: #216bc7;
}

/* Mobile */

@media(max-width: 800px) {

    .stats,
    .actions {
        grid-template-columns: 1fr;
    }

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

        <a href="dashboard.php">Dashboard</a>

        <a href="profile.php">Profile</a>

        <a href="skills.php">My Skills</a>

        <a href="matches.php">Find Partners</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>


<div class="container">

    <div class="welcome">

        <h1>
            Welcome, <?php echo htmlspecialchars($user_name); ?> 👋
        </h1>

        <p>
            Build your skills, connect with students and learn together.
        </p>

    </div>


    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card">

            <h3>
                My Skills
            </h3>

            <div class="number">
                <?php echo $total_skills; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>
                Connections
            </h3>

            <div class="number">
                <?php echo $total_connections; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>
                Profile
            </h3>

            <div class="number">
                100%
            </div>

        </div>

    </div>


    <!-- Actions -->

    <h2 class="section-title">
        What would you like to do?
    </h2>


    <div class="actions">


        <div class="action-card">

            <h3>
                🧠 Manage My Skills
            </h3>

            <p>
                Add the skills you know and the skills
                you want to learn.
            </p>

            <a href="skills.php" class="btn">
                Manage Skills
            </a>

        </div>


        <div class="action-card">

            <h3>
                🔍 Find Skill Partners
            </h3>

            <p>
                Find students who know the skills
                you want to learn.
            </p>

            <a href="matches.php" class="btn">
                Find Partners
            </a>

        </div>


        <div class="action-card">

            <h3>
                🤝 My Connections
            </h3>

            <p>
                View and manage your SkillConnect
                connections.
            </p>

            <a href="connections.php" class="btn">
                View Connections
            </a>

        </div>

    </div>

</div>

</body>

</html>
