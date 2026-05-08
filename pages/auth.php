<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}
?>

<main class="auth-container">
    <div class="auth-box">

        <div class="auth-tabs">
            <button id="tab-login" class="active" onclick="switchAuth('login')">
                LOGIN
            </button>

            <button id="tab-signup" onclick="switchAuth('signup')">
                SIGN UP
            </button>
        </div>

        <!-- LOGIN -->
        <form id="form-login" class="auth-form">
            <input type="hidden" name="type" value="login">

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn-auth">
                ENTER BEAUTYVERSE
            </button>
        </form>

        <!-- SIGNUP -->
        <form id="form-signup" class="auth-form" style="display:none;">
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

            <button type="submit" class="btn-auth">
                CREATE ACCOUNT
            </button>
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

document.querySelectorAll('.auth-form').forEach(form => {

    form.addEventListener('submit', async function(e) {

        e.preventDefault();

        const formData = new FormData(this);

        try {

            const response = await fetch('../process/auth_process.php', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();

            console.log(text);

            const result = JSON.parse(text);

            if (result.status === 'success') {

                alert(result.message);

                window.location.href = result.redirect;

            } else {

                alert(result.message);
            }

        } catch(error) {

            console.log(error);
            alert('Response error!');
        }
    });
});
</script>
<?php include '../includes/footer.php'; ?>