    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">Register for new account</h2>
            </div>
            <div class="panel-body">
              <form method="post" action="index.php?page=register" name="registerform" class="form-signin" role="form">

                <div class="form-group">
                  <input id="login_input_username" pattern="[a-zA-Z0-9]{2,64}" name="user_name" type="username" class="form-control" placeholder="Username" required autofocus>
                </div>

                <div class="form-group">
                  <input id="login_input_email" class="form-control" placeholder="user@domain.com" type="email" name="user_email" required>
                </div>

                <div class="form-group">
                  <input id="login_input_password_new" name="user_password_new" type="password" pattern=".{6,}" class="form-control" placeholder="Password" required autocomplete="off">
                </div>

                <div class="form-group">
                  <input id="login_input_password_repeat" name="user_password_repeat" type="password" pattern=".{6,}" class="form-control" placeholder="Password again" required autocomplete="off">
                </div>

                <div class="form-group">
                  <button class="btn btn-lg btn-primary btn-block" name="register" type="submit">Register</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>