<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT name, email, college, created_at
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();


$skills = $conn->prepare("
    SELECT s.skill_name, us.skill_type
    FROM user_skills us
    JOIN skills s ON us.skill_id = s.id
    WHERE us.user_id = ?
");

$skills->bind_param("i", $user_id);
$skills->execute();

$skill_result = $skills->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Profile - SkillConnect</title>

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
    margin-left: 20px;
}

.container {
    padding: 45px 8%;
    max-width: 1000px;
    margin: auto;
}

.profile-header {
    background: white;
    padding: 35px;
    border-radius: 18px;

    display: flex;
    align-items: center;
    gap: 25px;

    box-shadow: 0 10px 30px rgba(0,0,0,0.06);

    margin-bottom: 25px;
}

.avatar {
    width: 85px;
    height: 85px;

    border-radius: 50%;

    background: #eef5ff;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 38px;
}

.profile-header h1 {
    color: #123c69;
    margin-bottom: 8px;
}

.profile-header p {
    color: #64748b;
}

.card {
    background: white;
    padding: 30px;
    border-radius: 18px;

    box-shadow: 0 10px 30px rgba(0,0,0,0.06);

    margin-bottom: 25px;
}

.card h2 {
    color: #123c69;
    margin-bottom: 20px;
}

.info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.info-box {
    background: #f8fafc;
    padding: 18px;
    border-radius: 10px;
}

.label {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 6px;
}

.value {
    font-weight: bold;
    color: #1f2937;
}

.skills {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.skill {
    padding: 10px 15px;
    border-radius: 20px;
    background: #eef5ff;
    color: #123c69;
    font-weight: bold;
}

.want {
    background: #f1f5f9;
}

.empty {
    color: #64748b;
}

@media(max-width: 700px) {

    nav {
        flex-direction: column;
        gap: 15px;
    }

    nav a {
        margin: 0 8px;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
    }

    .info {
        grid-template-columns: 1fr;
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

        <a href="profile.php">
            Profile
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

    <div class="profile-header">

        <div class="avatar">
            👩‍🎓
        </div>

        <div>

            <h1>
                <?php echo htmlspecialchars($user["name"]); ?>
            </h1>

            <p>
                SkillConnect Student Profile
            </p>

        </div>

    </div>


    <div class="card">

        <h2>
            Personal Information
        </h2>

        <div class="info">

            <div class="info-box">

                <div class="label">
                    Full Name
                </div>

                <div class="value">
                    <?php echo htmlspecialchars($user["name"]); ?>
                </div>

            </div>


            <div class="info-box">

                <div class="label">
                    Email
                </div>

                <div class="value">
                    <?php echo htmlspecialchars($user["email"]); ?>
                </div>

            </div>


            <div class="info-box">

                <div class="label">
                    College
                </div>

                <div class="value">
                    <?php echo htmlspecialchars($user["college"]); ?>
                </div>

            </div>


            <div class="info-box">

                <div class="label">
                    Member Since
                </div>

                <div class="value">
                    <?php
                    echo date(
                        "d M Y",
                        strtotime($user["created_at"])
                    );
                    ?>
                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <h2>
            My Skills
        </h2>

        <?php if ($skill_result->num_rows > 0) { ?>

            <div class="skills">

                <?php while ($skill = $skill_result->fetch_assoc()) { ?>

                    <span class="skill <?php
                        echo $skill["skill_type"] == "want"
                            ? "want"
                            : "";
                    ?>">

                        <?php
                        echo htmlspecialchars(
                            $skill["skill_name"]
                        );
                        ?>

                        <?php
                        echo $skill["skill_type"] == "know"
                            ? " ✓"
                            : " • Learning";
                        ?>

                    </span>

                <?php } ?>

            </div>

        <?php } else { ?>

            <p class="empty">
                No skills added yet.
            </p>

        <?php } ?>

    </div>

</div>

</body>

</html>
