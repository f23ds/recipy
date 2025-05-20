<?php
session_start();
?>

<nav class="navbar">
    <a href="index.php" class="logo">recipy</a>

    <div class="navbar-right">
        <ul class="nav-links">
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="edit-profile.php">Profile</a></li>
                <li><a href="#" id="logoutLink">Logout</a></li>
            <?php endif; ?>
        </ul>

        <?php if (!isset($_SESSION['username'])): ?>
            <a href="login/login_form.php" class="btn-login">
                <i class="fas fa-user"></i> Sign in
            </a>
        <?php endif; ?>
    </div>
</nav>

<?php if (isset($_SESSION['username'])): ?>
    <div class="modal-overlay" id="logout-modal">
        <div class="modal-content">
            <h3>Are you sure you want to logout?</h3>
            <div class="modal-buttons">
                <a href="logout.php" class="btn-confirm">Yes, logout</a>
                <button class="btn-cancel" id="cancel-logout">Cancel</button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const logoutLink = document.getElementById('logoutLink');
        const modal = document.getElementById('logout-modal');
        const cancelBtn = document.getElementById('cancel-logout');

        if (logoutLink && modal && cancelBtn) {
            logoutLink.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.add('active');
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.remove('active');
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }
    });
</script>