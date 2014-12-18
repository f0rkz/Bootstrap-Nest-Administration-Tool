    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">Please sign in</h2>
            </div>
            <div class="panel-body">
              <form method="post" action="index.php" name="loginform" class="form-signin" role="form">

                <div class="form-group">
                  <input id="login_input_username" name="user_name" type="username" class="form-control input-lg" placeholder="Username" required autofocus>
                </div>

                <div class="form-group">
                  <input id="login_input_password" name="user_password" type="password" class="form-control input-lg" placeholder="Password" required>
                </div>

                <div class="form-group">
                  <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign in</button>
                </div>

                <div class="form-group">
                  <a href="index.php?page=register" class="btn btn-lg btn-primary btn-block">Register</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>