    <div class="container">

      <form method="post" action="?page=profile&postsettings=update" name="profileform" class="form-profile" role="form">
        <h2 class="form-profile-heading">Nest profile options</h2>
        <input id="nest_input_username" name="nest[username]" type="username" class="form-control" placeholder="Nest Username" value="<?= $nest_username; ?>" required autofocus>
        <input id="nest_input_password" name="nest[password]" type="password" class="form-control" placeholder="Nest Password" required>
        <input id="nest_location" name="nest[location]" class="form-control" placeholder="Zipcode" value="<?= $zipcode; ?>" required>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" name="postsettings">Submit</button>
      </form>
    </div>