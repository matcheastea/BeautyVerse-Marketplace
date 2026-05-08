<?php
include '../includes/db.php';
include '../includes/header.php';

$conn = mysqli_connect($host, $username, $pass, $db);

session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>

<main class="profile-container">
    <div class="profile-wrapper">
        <aside class="profile-sidebar">
            <div class="user-meta">
                <div class="avatar-circle">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h3><?php echo $user['name']; ?></h3>
                <p><?php echo $user['email']; ?></p>
            </div>
            <nav class="profile-nav">
                <a href="#" class="active">Account Settings</a>
                <a href="#">Order History</a>
                <a href="#">Skin Analysis Result</a>
                <a href="../process/logout.php" class="logout">Logout</a>
            </nav>
        </aside>

        <section class="profile-content">
            <h2>Account Detail</h2>
            <form action="../process/update_profile.php" method="POST" class="luxury-form">
                <div class="form-section">
                    <h3>Basic Information</h3>
                    <div class="form-grid">
                        <div class="field">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="field">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?=  $user['phone'] ??''?>" required> 
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Shipping Address</h3>
                    <div class="form-grid">
                        <div class="field">
                            <label>City</label>
                            <input type="text" name="city" value="<?= $user['city'] ?? ''?>" required>
                        </div>
                        <div class="field">
                            <label>Complete Address</label>
                            <textarea name="complete_address" rows="3"><?= $user['complete_address'] ?? '' ?></textarea>
                        </div>
                    </div>
                    <div class="field">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" value="<?= $user['postal_code']?? '' ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn-save">SAVE CHANGES</button>
            </form>
        </section>
    </div>
</main>

<?php
include '../includes/footer.php';
?>