<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('INSERT INTO users (username, psw) VALUES (?, ?)');
  
    if ($stmt->execute([$username, $password])) {
        header('Location: login.php');
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login_style.css">
    <title>Login | Ludiflex</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Register</header>
        </div>
        <form method="POST" action="register.php">
        <div class="input-box">
            <input type="text" name="username"  class="input-field" placeholder="Username" autocomplete="off" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
        </div>
      
        <div class="input-submit">
            <button class="submit-btn" id="submit"></button>
            <label for="submit" type="submit">Register</label>
        </div>
        <div class="sign-up-link">
            <p>Register Here </p>
        </div>
       
    </div>
</body>
</html>
