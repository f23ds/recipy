<?php
session_start();
?>

<div id="appHeader">
    <nav class="navbar">
        <div class="logo">recipy</div>

        <div class="navbar-right">
            <ul class="nav-links">
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
                    <li><a href="../explore/explore.php">Explore</a></li>
                    <li><a href="../header/edit-profile-vue.php">Profile</a></li>
                <?php endif; ?>
                <li><a href="../header/contact.php">Contact</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="#" @click.prevent="showModal">Logout</a></li>
                <?php endif; ?>
            </ul>

            <?php if (!isset($_SESSION['username'])): ?>
                <a href="../login/login_form.php" class="btn-login">
                    <i class="fas fa-user"></i> Sign in
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="modal-overlay" :class="{ active: showLogoutModal }" @click.self="hideModal">
        <div class="modal-content">
            <h3>Are you sure you want to logout?</h3>
            <div class="modal-buttons">
                <button class="btn-cancel" @click="hideModal">Cancel</button>
                <a href="../header/logout.php" class="btn-confirm">Yes, logout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3"></script>
<script>
    Vue.createApp({
        data() {
            return {
                showLogoutModal: false
            };
        },
        methods: {
            showModal() {
                this.showLogoutModal = true;
            },
            hideModal() {
                this.showLogoutModal = false;
            }
        }
    }).mount('#appHeader');

</script>,