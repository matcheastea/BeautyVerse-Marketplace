<?php 
include '../includes/db.php'; 
include '../includes/header.php'; 
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}
?>

<main class="auth-container">
    <div class="auth-box">
        <div class="auth-tabs">
            <button id="tab-login" class="active" onclick="switchAuth('login')">LOGIN</button>
            <button id="tab-signup" onclick="switchAuth('signup')">SIGN UP</button>
        </div>

        <form id="form-login" action="../process/auth_process.php" method="POST" class="auth-form">
            <input type="hidden" name="type" value="login">
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-auth">ENTER BEAUTYVERSE</button>
        </form>

        <form id="form-signup" action="../process/auth_process.php" method="POST" class="auth-form" style="display: none;">
            <input type="hidden" name="type" value="signup">
            <div class="field">
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="field">
                <label>Create Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-auth">CREATE ACCOUNT</button>
        </form>
    </div>
</main>

<script>
function switchAuth(type) {
    const loginForm = document.getElementById('form-login');
    const signupForm = document.getElementById('form-signup');
    const loginTab = document.getElementById('tab-login');
    const signupTab = document.getElementById('tab-signup');

    if (type === 'login') {
        loginForm.style.display = 'block';
        signupForm.style.display = 'none';
        loginTab.classList.add('active');
        signupTab.classList.remove('active');
    } else {
        loginForm.style.display = 'none';
        signupForm.style.display = 'block';
        signupTab.classList.add('active');
        loginTab.classList.remove('active');
    }
}
</script>

<?php include '../includes/footer.php'; ?>