    <div class="container">

      <form method="post" action="/?page=profile" name="profileform" class="form-profile" role="form">
        <h2 class="form-profile-heading">Nest profile options for <?= $username; ?></h2>
        <input id="nest_input_username" name="nest_user_name" type="username" class="form-control" placeholder="Nest Username" value="<?= $nest_user; ?>" required autofocus>
        <input id="nest_input_password" name="user_password" type="password" class="form-control" placeholder="Nest Password" value="<?= $nest_pass; ?>" required>
        <input id="nest_location" name="nest_location_zip" class="form-control" placeholder="Zipcode" value="<?= $zipcode; ?>" required>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Submit</button>
      </form>
    </div>

    <div class="container">
		<h3>Current settings</h3>
    </div>