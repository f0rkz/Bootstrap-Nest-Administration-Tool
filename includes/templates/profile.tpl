    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">Nest profile options</h2>
            </div>
            <div class="panel-body">
              <form method="post" action="?page=profile&postsettings=update" name="profileform" class="form-profile" role="form">

                <div class="form-group">
                  <input data-toggle="tooltip" data-placement="right" title="This is your Nest Username" id="nest_input_username" name="nest[username]" type="username" class="form-control" placeholder="Nest Username" value="<?= $nest_username; ?>" required autofocus>
                </div>

                <div class="form-group">
                  <input data-toggle="tooltip" data-placement="right" title="This is your Nest Password"  id="nest_input_password" name="nest[password]" type="password" class="form-control" placeholder="Nest Password" required>
                </div>

                <div class="form-group">
                  <input data-toggle="tooltip" data-placement="right" title="This is your Zipcode/Postcode" id="nest_location" name="nest[location]" class="form-control" placeholder="Location" value="<?= $location; ?>" required>
                </div>

                <div class="form-group">
                  <button class="btn btn-lg btn-primary btn-block" type="submit" name="postsettings">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
