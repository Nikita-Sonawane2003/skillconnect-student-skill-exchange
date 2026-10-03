<?php
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $college = trim($_POST["college"]);
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($college) || empty($password)) {
        $message = "Please fill all fields.";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, college, password)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $college,
            $hashed_password
        );

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Registration failed. Email may already exist.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - SkillConnect</title>

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
            width: 450px;
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
            margin-bottom: 18px;
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
        }

        .login-link a {
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
        <a href="login.php">Login</a>
    </div>

</nav>

<div class="container">

    <div class="card">

        <h1>Create Account</h1>

        <p class="subtitle">
            Join SkillConnect and start learning together.
        </p>

        <?php if (!empty($message)) { ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <form method="POST">

            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter your full name"
                required
            >

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>College Name</label>

            <input
                type="text"
                name="college"
                placeholder="Enter your college"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Create a password"
                required
            >

            <button type="submit">
                Create Account
            </button>

        </form>

        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>

</div>

</body>
</html>
