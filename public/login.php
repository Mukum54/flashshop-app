<?php
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    $user = loginUser($email, $password, $pdo);
    if ($user) {
        migrateSessionToUser($user['id'], $pdo);
        redirect('dashboard.php');
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<main class="container">
    <div style="max-width: 400px; margin: 4rem auto; background: var(--card-bg); padding: 2.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow);">
        <h1 style="margin-bottom: 2rem; text-align: center;">Welcome Back</h1>
        
        <?php if ($error): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #fecaca;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <label style="font-weight: 600;">Password</label>
                    <a href="#" style="font-size: 0.85rem; color: var(--primary-color); text-decoration: none;">Forgot?</a>
                </div>
                <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
            </div>

            <button type="submit" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-bottom: 1.5rem;">Sign In</button>
            
            <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                Don't have an account? <a href="register.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Sign up</a>
            </p>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
