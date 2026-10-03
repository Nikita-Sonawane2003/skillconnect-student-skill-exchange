<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillConnect</title>

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
            margin-left: 25px;
            font-size: 15px;
        }

        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 60px 8%;
            gap: 50px;
        }

        .hero-text {
            max-width: 600px;
        }

        .hero-text h1 {
            font-size: 52px;
            color: #123c69;
            margin-bottom: 20px;
        }

        .hero-text h1 span {
            color: #2f80ed;
        }

        .hero-text p {
            font-size: 19px;
            line-height: 1.7;
            color: #64748b;
            margin-bottom: 30px;
        }

        .buttons a {
            display: inline-block;
            padding: 14px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-right: 12px;
            font-weight: bold;
        }

        .register {
            background: #2f80ed;
            color: white;
        }

        .login {
            border: 2px solid #2f80ed;
            color: #2f80ed;
        }

        .hero-card {
            width: 390px;
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .hero-card h2 {
            color: #123c69;
            margin-bottom: 20px;
        }

        .skill {
            background: #eef5ff;
            padding: 14px;
            border-radius: 10px;
            margin: 12px 0;
        }

        .skill strong {
            color: #123c69;
        }

        .arrow {
            text-align: center;
            font-size: 25px;
            color: #2f80ed;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #123c69;
            color: white;
        }

        @media(max-width: 800px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 40px;
            }

            .hero-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<nav>
    <div class="logo">SkillConnect</div>

    <div>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</nav>

<section class="hero">

    <div class="hero-text">

        <h1>Connect. Learn. <span>Grow.</span></h1>

        <p>
            SkillConnect helps students find the right people to learn
            from and share their own skills with others.
        </p>

        <div class="buttons">
            <a href="register.php" class="register">Get Started</a>
            <a href="login.php" class="login">Login</a>
        </div>

    </div>

    <div class="hero-card">

        <h2>How SkillConnect Works</h2>

        <div class="skill">
            <strong>1. Add Your Skills</strong>
            <br>
            Tell us what you already know.
        </div>

        <div class="arrow">↓</div>

        <div class="skill">
            <strong>2. Choose What You Want</strong>
            <br>
            Select skills you want to learn.
        </div>

        <div class="arrow">↓</div>

        <div class="skill">
            <strong>3. Find Your Match</strong>
            <br>
            Discover students who can help you.
        </div>

    </div>

</section>

<footer>
    © 2026 SkillConnect | Student Skill Exchange Platform
</footer>

</body>
</html>
