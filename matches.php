<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$user_id = $_SESSION["user_id"];

/*
Find students who know skills
that the current user wants to learn.
*/

$sql = "
SELECT DISTINCT
    u.id,
    u.name,
    u.college,
    s.skill_name
FROM users u
JOIN user_skills us
    ON u.id = us.user_id
JOIN skills s
    ON us.skill_id = s.id
WHERE us.skill_type = 'know'
AND us.skill_id IN (
    SELECT skill_id
    FROM user_skills
    WHERE user_id = ?
    AND skill_type = 'want'
)
AND u.id != ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $user_id,
    $user_id
);

$stmt->execute();

$matches = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Find Skill Partners - SkillConnect</title>

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

/* Match cards */

.matches {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(280px, 1fr));

    gap: 25px;
}

.card {
    background: white;
    padding: 28px;
    border-radius: 16px;

    box-shadow:
        0 10px 30px rgba(0,0,0,0.06);
}

.avatar {
    width: 60px;
    height: 60px;

    border-radius: 50%;

    background: #eef5ff;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 25px;

    margin-bottom: 15px;
}

.card h2 {
    color: #123c69;
    margin-bottom: 7px;
}

.college {
    color: #64748b;
    margin-bottom: 20px;
}

.skill {
    display: inline-block;

    background: #eef5ff;
    color: #123c69;

    padding: 9px 14px;

    border-radius: 20px;

    font-weight: bold;

    margin-bottom: 20px;
}

.connect-btn {
    display: block;

    width: 100%;

    text-align: center;

    padding: 12px;

    background: #2f80ed;

    color: white;

    text-decoration: none;

    border-radius: 8px;

    font-weight: bold;
}

.connect-btn:hover {
    background: #216bc7;
}

/* Empty */

.empty {
    background: white;

    padding: 40px;

    border-radius: 15px;

    text-align: center;

    color: #64748b;
}

/* Mobile */

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

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>


<div class="container">

    <div class="heading">

        <h1>
            Find Your Skill Partners 🔍
        </h1>

        <p>
            Students who know the skills you want to learn.
        </p>

    </div>


    <?php if ($matches->num_rows > 0) { ?>

        <div class="matches">

            <?php while ($match = $matches->fetch_assoc()) { ?>

                <div class="card">

                    <div class="avatar">
                        👨‍🎓
                    </div>

                    <h2>
                        <?php
                        echo htmlspecialchars($match["name"]);
                        ?>
                    </h2>

                    <p class="college">
                        <?php
                        echo htmlspecialchars($match["college"]);
                        ?>
                    </p>

                    <span class="skill">

                        Knows:
                        <?php
                        echo htmlspecialchars(
                            $match["skill_name"]
                        );
                        ?>

                    </span>

                    <a
                        
                       href="connect.php?user_id=<?php echo $match['id']; ?>"
                       class="connect-btn"

   

                        
                    >
                        Connect
                    </a>

                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="empty">

            <h2>
                No matches found yet
            </h2>

            <br>

            <p>
                Add some skills you want to learn
                from the My Skills page.
            </p>

        </div>

    <?php } ?>

</div>

</body>

</html>
