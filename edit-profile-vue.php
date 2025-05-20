<?php
  session_start();
  $username = $_SESSION['username'] ?? null;
  $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
          or die('Could not connect: ' . pg_last_error());

  $q0 = "SELECT * FROM users WHERE username = $1";
  $result = pg_query_params($dbconn, $q0, array($username));

  if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
      echo "<h1>Query error</h1>";
  }

  $name = $tuple['name'];
  $email = $tuple['email'];
  $profile_pic = $tuple['profile_pic'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profile - Recipy</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/edit-profile.css" />
  <link rel="stylesheet" href="css/auth.css" />  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <?php include 'components/header.php'; ?>

  <main class="edit-profile-container">
    <h1>Edit your profile</h1>

    <div id="app">
      <form class="edit-form" @submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="profile-image-section"
             @mouseenter="hovering = true"
             @mouseleave="hovering = false">
          <label for="profileImage" class="profile-img-label" :class="{ disabled: readonly }">
            <img :src="preview || profilePic" alt="Profile Picture" id="profilePreview" />
            <div class="edit-icon">
              <i class="fas fa-pen"></i>
            </div>
          </label>
          <input 
            type="file" 
            id="profileImage" 
            @change="onImageChange" 
            accept="image/*" 
            :disabled="readonly" 
            hidden
          />
          <p class="edit-warning" v-if="readonly && hovering">
            Click "Edit All" to change your profile picture
          </p>
        </div>

        <label for="username">Username</label>
        <input type="text" id="username" v-model="form.username" :readonly="readonly" />
        <p class="error" v-if="errors.username">{{ errors.username }}</p>

        <label for="email">Email</label>
        <input type="email" id="email" v-model="form.email" :readonly="readonly" />
        <p class="error" v-if="errors.email">{{ errors.email }}</p>

        <label for="password">New Password <span>(optional)</span></label>
        <input type="password" id="password" v-model="form.password" :readonly="readonly" placeholder="••••••••" />
        <p class="error" v-if="errors.error">{{ errors.error }}</p>

        <div class="form-actions">
          <button type="button" class="btn-secondary" @click="enableEdit" v-if="readonly">Edit All</button>
          <button type="submit" class="btn-primary" v-if="!readonly">Save Changes</button>
          <a href="dashboard.php" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </main>
  <?php include 'components/footer.php'; ?>
  <script src="https://unpkg.com/vue@3"></script>
  <script>
    let app = Vue.createApp({
      data() {
        return {
          profilePic: '<?php echo $profile_pic; ?>',
          preview: null,
          readonly: true,
          hovering: false,
          form: {
            username: '<?php echo $username; ?>',
            email: '<?php echo $email; ?>',
            password: '',
            profileImage: null
          },
          errors: {}
        };
      },
      methods: {
        enableEdit() {
          this.readonly = false;
        },
        onImageChange(event) {
          if (this.readonly) return;
          const file = event.target.files[0];
          if (file) {
            this.form.profileImage = file;
            this.preview = URL.createObjectURL(file);
          }
        },
        submitForm() {
          let no_changes=true;

          if (!this.form.username.trim()) {
            this.errors.username = '❌ Username cannot be empty';
            no_changes=false;
          }

          if (!this.form.email.trim()) {
            this.errors.email = '❌ Email cannot be empty';
            no_changes=false;
          }

          if (!no_changes) return;
          const formData = new FormData();
          formData.append('username', this.form.username);
          formData.append('email', this.form.email);
          formData.append('password', this.form.password);
          if (this.form.profileImage) {
            formData.append('profileImage', this.form.profileImage);
          }
        
          fetch('edit-profile-db.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              this.errors = {};
              this.readonly = true;
              this.form.password = '';
              if (this.preview) {
                this.profilePic = this.preview;
                this.preview = null;
              }
            } else {
              this.errors = data;
            }
          })
          .catch(err => {
            console.error(err);
          });
        }
      }
    });

    app.mount('#app');

  </script>
</body>
</html>
