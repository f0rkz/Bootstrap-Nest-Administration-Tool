    <div class="container">
      <form method="post" action="index.php?page=register" name="registerform" class="form-signin" role="form">
        <h2 class="form-signin-heading">Register for new Account</h2>
        <input id="login_input_username" pattern="[a-zA-Z0-9]{2,64}" name="user_name" type="username" class="form-control" placeholder="Username" required autofocus>
        <input id="login_input_email" class="form-control" placeholder="user@domain.com" type="email" name="user_email" required>
        <input id="login_input_password_new" name="user_password_new" type="password" pattern=".{6,}" class="form-control" placeholder="Password" required autocomplete="off">
        <input id="login_input_password_repeat" name="user_password_repeat" type="password" pattern=".{6,}" class="form-control" placeholder="Password again" required autocomplete="off">
        <button class="btn btn-lg btn-primary btn-block" name="register" type="submit">Register</button>
      </form>
    </div>