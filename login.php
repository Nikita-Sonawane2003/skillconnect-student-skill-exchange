<?php
session_start();
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Invalid password.";
        }

    } else {
        $message = "Account not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - SkillConnect</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f7fb;
    min-height: 100vh;
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
    margin-left: 25px;
}

.container {
    min-height: 85vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

.card {
    background: white;
    width: 420px;
    padding: 40px;
    border-radius: 18px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
}

.card h1 {
    text-align: center;
    color: #123c69;
    margin-bottom: 10px;
}

.subtitle {
    text-align: center;
    color: #64748b;
    margin-bottom: 30px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    color: #334155;
}

input {
    width: 100%;
    padding: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 15px;
}

input:focus {
    outline: none;
    border-color: #2f80ed;
}

button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 8px;
    background: #2f80ed;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #216bc7;
}

.message {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px;
    border-radius: 7px;
    margin-bottom: 18px;
    text-align: center;
}

.register-link {
    text-align: center;
    margin-top: 20px;
    color: #64748b;
}

.register-link a {
    color: #2f80ed;
    text-decoration: none;
    font-weight: bold;
}

</style>

</head>

<body>

<nav>

    <div class="logo">
        SkillConnect
    </div>

    <div>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </div>

</nav>

<div class="container">

    <div class="card">

        <h1>Welcome Back</h1>

        <p class="subtitle">
            Login to continue your SkillConnect journey.
        </p>

        <?php if (!empty($message)) { ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <form method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

        <div class="register-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>

    </div>

</div>

</body>
</html>
