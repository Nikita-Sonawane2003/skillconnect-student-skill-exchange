<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $skill_id = $_POST["skill_id"];
    $skill_type = $_POST["skill_type"];

    $check = $conn->prepare("
        SELECT * FROM user_skills
        WHERE user_id = ?
        AND skill_id = ?
        AND skill_type = ?
    ");

    $check->bind_param(
        "iis",
        $user_id,
        $skill_id,
        $skill_type
    );

    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {

        $insert = $conn->prepare("
            INSERT INTO user_skills
            (user_id, skill_id, skill_type)
            VALUES (?, ?, ?)
        ");

        $insert->bind_param(
            "iis",
            $user_id,
            $skill_id,
            $skill_type
        );

        $insert->execute();
    }
}

$skills = $conn->query("SELECT * FROM skills");

$my_skills = $conn->query("
    SELECT s.skill_name, us.skill_type
    FROM user_skills us
    JOIN skills s ON us.skill_id = s.id
    WHERE us.user_id = $user_id
");
?>
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Skills - SkillConnect</title>

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

.heading {
    margin-bottom: 30px;
}

.heading h1 {
    color: #123c69;
    margin-bottom: 8px;
}

.heading p {
    color: #64748b;
}

/* Add skill */

.add-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    margin-bottom: 35px;
}

.add-card h2 {
    color: #123c69;
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

select {
    flex: 1;
    min-width: 200px;
    padding: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: white;
}

button {
    padding: 13px 25px;
    background: #2f80ed;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #216bc7;
}

/* Skills */

.skills-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.skill-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.skill-card h2 {
    color: #123c69;
    margin-bottom: 20px;
}

.skill-item {
    display: inline-block;
    background: #eef5ff;
    color: #123c69;
    padding: 10px 15px;
    border-radius: 20px;
    margin: 5px;
    font-weight: bold;
}

.want {
    background: #f1f5f9;
}

.empty {
    color: #64748b;
}

/* Mobile */

@media(max-width: 800px) {

    .skills-grid {
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

        <a href="skills.php">My Skills</a>

        <a href="matches.php">Find Partners</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>


<div class="container">

    <div class="heading">

        <h1>My Skills</h1>

        <p>
            Tell us what you know and what you want to learn.
        </p>

    </div>


    <!-- Add Skill -->

    <div class="add-card">

        <h2>Add a Skill</h2>

        <form method="POST">

            <div class="form-row">

                <select name="skill_id" required>

                    <option value="">
                        Select Skill
                    </option>

                    <?php

                    $skills->data_seek(0);

                    while ($skill = $skills->fetch_assoc()) {

                    ?>

                        <option value="<?php echo $skill["id"]; ?>">

                            <?php echo htmlspecialchars($skill["skill_name"]); ?>

                        </option>

                    <?php } ?>

                </select>


                <select name="skill_type" required>

                    <option value="">
                        Skill Type
                    </option>

                    <option value="know">
                        I Know This
                    </option>

                    <option value="want">
                        I Want To Learn
                    </option>

                </select>


                <button type="submit">
                    + Add Skill
                </button>

            </div>

        </form>

    </div>


    <!-- My Skills -->

    <div class="skills-grid">


        <div class="skill-card">

            <h2>
                🧠 Skills I Know
            </h2>

            <?php

            $has_known = false;

            $my_skills->data_seek(0);

            while ($skill = $my_skills->fetch_assoc()) {

                if ($skill["skill_type"] == "know") {

                    $has_known = true;

            ?>

                    <span class="skill-item">

                        <?php echo htmlspecialchars($skill["skill_name"]); ?>

                    </span>

            <?php

                }
            }

            if (!$has_known) {

                echo '<p class="empty">
                        No skills added yet.
                      </p>';

            }

            ?>

        </div>


        <div class="skill-card">

            <h2>
                🎯 Skills I Want To Learn
            </h2>

            <?php

            $has_wanted = false;

            $my_skills->data_seek(0);

            while ($skill = $my_skills->fetch_assoc()) {

                if ($skill["skill_type"] == "want") {

                    $has_wanted = true;

            ?>

                    <span class="skill-item want">

                        <?php echo htmlspecialchars($skill["skill_name"]); ?>

                    </span>

            <?php

                }
            }

            if (!$has_wanted) {

                echo '<p class="empty">
                        No learning goals added yet.
                      </p>';

            }

            ?>

        </div>

    </div>

</div>

</body>

</html>
