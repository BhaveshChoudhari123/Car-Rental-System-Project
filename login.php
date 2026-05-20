<?php
session_start();
require 'inc/db.php';

$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id,name,password FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: cars.php");
            exit();
        } else {
            $errors[] = "Invalid password";
        }
    } else {
        $errors[] = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - DriveNow</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            background-color: rgba(255,255,255,0.05);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 30px;
            margin-bottom: 24px;
            color: #facc15;
            text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #475569;
            background-color: #1e293b;
            color: #f1f5f9;
            font-size: 16px;
        }

        input::placeholder {
            color: #94a3b8;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #facc15;
            color: #1f2937;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #eab308;
            transform: scale(1.03);
        }

        .error {
            color: #f87171;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 12px;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #facc15;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        @media(max-width: 600px){
            .login-card {
                padding: 24px;
            }

            h2 {
                font-size: 24px;
            }

            button {
                font-size: 14px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login</h2>
    <?php foreach($errors as $err) echo "<div class='error'>$err</div>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="register.php">Register</a></p>
</div>

</body>
</html>