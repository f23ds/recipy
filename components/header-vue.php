<?php session_start(); ?>

<div id="app">
  <nav class="navbar">
    <a href="index.php" class="logo">recipy</a>

    <div class="navbar-right">
      <ul class="nav-links">
        <?php if (isset($_SESSION['username'])): ?>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="explore.php">Explore</a></li>
          <li><a href="edit-profile-vue.php">Profile</a></li>
        <?php endif; ?>
        <li><a href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['username'])): ?>
          <li><a href="#" @click.prevent="showModal">Logout</a></li>
        <?php endif; ?>
      </ul>

      <?php if (!isset($_SESSION['username'])): ?>
        <a href="login/login_form.php" class="btn-login">
          <i class="fas fa-user"></i> Sign in
        </a>
      <?php endif; ?>
    </div>
  </nav>

  <div class="modal-overlay" :class="{ active: showLogoutModal }" @click.self="hideModal">
    <div class="modal-content">
      <h3>Are you sure you want to logout?</h3>
      <div class="modal-buttons">
        <a href="logout.php" class="btn-confirm">Yes, logout</a>
        <button class="btn-cancel" @click="hideModal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/vue@3"></script>
<script>
  const app = Vue.createApp({
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
  });

  app.mount('#app');

</script>
