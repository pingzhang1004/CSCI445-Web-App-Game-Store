<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Unit6_login.css"> 
    <title>Login Page</title>
</head>
<body>
    <?php include 'Unit6_header.php'; ?>
    <h3>
        Welcome! Please login or select Continue as Guest to begin.
        <?php if (isset($_GET['err'])): ?>
            <span style="color:red;"><?= htmlspecialchars($_GET['err']); ?></span>
        <?php endif; ?>
    </h3>

    <div class="login-container">    
        <form action="Unit6_login.php" method="post">
            <div class="form-group">
                <label class = "required" for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required autocomplete="email">
                <label for="password" class = "required">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required autocomplete="current-password">
            </div>
            <div class="form-options">
                <label>
                    <input type="checkbox" name="remember" checked="checked"> Remember me
                </label>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>
            <button type="submit" class="login-button">Login</button>
            <button onclick="location.href='Unit6_store.php'" type="button" class="guest-button">Continue as Guest</button>
        </form>
    </div>

    <?php include 'Unit6_footer.php'; ?>
</body>
</html>
