<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="center">

<div class="card">
<h2>SmartTask Login</h2>

<?php if(isset($_GET['error'])) echo "<p class='error'>Invalid Login</p>"; ?>

<form action="../controllers/AuthController.php" method="POST">
<input type="hidden" name="action" value="login">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
</form>

<p><a href="register.php">Create Account</a></p>
</div>
</body>
</html>