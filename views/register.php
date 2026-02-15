<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="center">

<div class="card">
<h2>Create Account</h2>

<form action="../controllers/AuthController.php" method="POST">
<input type="hidden" name="action" value="register">
<input type="text" name="username" placeholder="Username" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Register</button>
</form>

<p><a href="login.php">Login</a></p>
</div>
</body>
</html>