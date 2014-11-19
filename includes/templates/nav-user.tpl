    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="<?= $nav_brand_url; ?>" class="navbar-brand"><?= $nav_brand_name; ?></a>
        </div>
        <div class="navbar-collapse collapse navbar-inverse-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Graphs</a></li>
            <li><a href="index.php?page=profile">Settings</a></li>
            <li><a href="index.php#">Even more stuff</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php?logout">Logout</a></li>
            <li><a href="http://git.f0rkznet.net/f0rkz/bootstrap-nest-administration-tool"><i class="fa fa-git-square fa-lg"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
