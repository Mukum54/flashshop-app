<?php
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $user_id = registerUser($name, $email, $phone, $password, $pdo);
        if ($user_id) {
            loginUser($email, $password, $pdo);
            migrateSessionToUser($user_id, $pdo);
            redirect('dashboard.php');
        } else {
            $error = "Registration failed. Email might already be in use.";
        }
    }
}
?>

<main class="container">
    <div style="max-width: 500px; margin: 4rem auto; background: var(--card-bg); padding: 2.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow);">
        <h1 style="margin-bottom: 2rem; text-align: center;">Create Account</h1>

        <?php if ($error): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #fecaca;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Full Name</label>
                <input type="text" name="name" required placeholder="John Doe" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                    <input type="email" name="email" required placeholder="john@example.com" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Phone</label>
                    <input type="tel" name="phone" placeholder="+1 (555) 000-0000" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Password</label>
                <input type="password" name="password" required placeholder="Min. 8 characters" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="Repeat password" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
            </div>

            <button type="submit" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-bottom: 1.5rem;">Create Account</button>
            <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                Already have an account? <a href="login.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Sign in</a>
            </p>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
