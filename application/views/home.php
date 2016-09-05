
<?php if(!isset($this->session->userdata('user')['is_logged_in']) && !isset($this->session->userdata('user')['user_id'])) { ?>

<div class="login-page">
  <div class="">
    <?php if(!empty($email_exists)) echo 'This email is already registered'; ?>
    <?php if(!empty($invalid_login)) echo 'Invalid login details'; ?>
  </div>
  <div class="form">
    <form class="register-form" action="<?php echo base_url('/users/register'); ?>" method="post">
      <input type="text" name="name" required placeholder="name"/>
      <input type="password" name="password" required placeholder="password"/>
      <input type="email" name="email" required placeholder="email address"/>
      <button>create</button>
      <p class="message">Already registered? <a href="#">Sign In</a></p>
    </form>
    <form class="login-form" action="<?php echo base_url('/users/validate_user'); ?>" method="post">
      <input type="email" name = "email" required placeholder="email address"/>
      <input type="password" name = "password" required placeholder="password"/>
      <button class="" id="sign_up">Sign In</button>
      <p class="message">Not registered? <a href="#">Create an account</a></p>
    </form>
  </div>
</div>
<?php } else { ?>
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <h2>Welcome to  Real time notification system in PHP</h2>
      <div class="">
        A real time PHP notification system where a user can send his/her changes made in database to his
        subscribers and can get other's (publishers) changes done in database in real time
      </div>
      <div class="">
        <h3>publishers</h3>
        <div class="">
          <p>
            Publishers are those users who are active and from whom I (user who is logged in) want to
            receive notification, By default when a user is logged in all other users in database
            are his publisher.
            user can change or add and remove it's publisher at any time after clicking on add publisher or remove publisher
            respectively.
          </p>
          <p>
            Add Publisher  - means users list from which you want to receive their update
          </p>
          <p>
            Remove Publisher  - means users list from which you don't want to receive their update
          </p>
        </div>
        <div class="">
          <h3>subscribers</h3>
          <p>
            subscribers are those users to whom I (user who is logged in) want to send my update I have done to database,
            By default all users who are active in database are user's subscribers, user has privilage to change his/her
            subscribers at any time from add subscriber or remove subscriber respectively.
          </p>
          <p>
            Add subscriber  - means users list to whom user wants to send their updates in database
          </p>
          <p>
            Remove subscriber  - means users list to whom user don't want to send their update in database
          </p>
        </div>
        <div class="">

        </div>
      </div>
    </div>
  </div>
</div>
  <?php } ?>
