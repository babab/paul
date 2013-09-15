<?php
# Copyright (c) 2012, 2013  Benjamin Althues <benjamin@babab.nl>
#
# Permission to use, copy, modify, and distribute this software for any
# purpose with or without fee is hereby granted, provided that the above
# copyright notice and this permission notice appear in all copies.
#
# THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
# WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
# MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
# ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
# WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
# ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
# OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

session_start();
require_once 'paul/paul.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $cookie = new cookie_login(null);
    $cookie->authorize();
}

$paul = new paul;
$base_url         = $paul->base_url;
$token            = $paul->create_token();


if (!empty($_GET['cmd']))
    $cmd = htmlentities($_GET['cmd']);

/* Warning --  At line 14, the date() function is used
 * It is not safe to rely on the system's timezone settings. You are *required*
 * to set a value for the date.timezone setting in your systems php.ini or you
 * can use the date_default_timezone_set() function.
 */
date_default_timezone_set('Europe/Amsterdam');

?><!doctype html>
<html>
  <head>
    <link rel="stylesheet"
          href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
  </head>
  <body>
    <div id="container">
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <div style="height: 50px"></div>
          <div style="font-size: 16px">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

              logged in as
              <strong><?= $_SESSION['username'] ?></strong>

              <?php if (isset($_SESSION['last_seen'])): ?>
                | last login:
                <?php echo date("Y-m-d H:i:s", $_SESSION['last_seen']) ?>
              <?php endif ?>

              | <a href="<?= $base_url ?>paul/auth.php?logout">logout</a>

            <?php else: ?>
              not logged in
              | <a href="<?= $base_url ?>example.php?cmd=login">login</a>
              | <a href="<?= $base_url ?>example.php?cmd=register">register</a>
            <?php endif ?>
          </div>
          <div style="height: 50px"></div>

          <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
          <?php endif ?>

          <?php if (isset($cmd) && ($cmd == 'login' || $cmd == 'register')): ?>
            <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>

              <form method="post" class="form-horizontal" role="form"
                    action="<?= $base_url ?>paul/auth.php">

                <div class="form-group">
                  <label for="username" class="col-lg-2 control-label">username</label>
                  <div class="col-lg-10">
                    <?php if (!empty($_SESSION['username_inp'])): ?>
                      <input type="text" id="username" name="username"
                             class="form-control" placeholder="username"
                             value="<?= $_SESSION['username_inp'] ?>">
                    <?php else: ?>
                      <input type="text" id="username" name="username"
                             class="form-control" placeholder="username">
                    <?php endif ?>
                  </div>
                </div>

                <div class="form-group">
                  <label for="password" class="col-lg-2 control-label">password</label>
                  <div class="col-lg-10">
                    <input type="password" id="password" name="password"
                           class="form-control" placeholder="password">
                  </div>
                </div>

                <?php if ($cmd == 'register'): ?>
                  <div class="form-group">
                    <label for="password2" class="col-lg-2 control-label">password (again)</label>
                    <div class="col-lg-10">
                      <input type="password" id="password2" name="password2"
                             class="form-control" placeholder="password (again)">
                    </div>
                  </div>
                <?php else: ?>
                  <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" id="remember_me" name="remember_me"> Remember me
                        </label>
                      </div>
                    </div>
                  </div>
                <?php endif ?>

                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-default">
                      <?= $cmd ?>
                    </button>
                  </div>
                </div>

                <input type="hidden" id="token" name="token"
                       value="<?= $token ?>">
              </form>
            <?php else: ?>

              <p>
                you are already logged in as
                <?= $_SESSION['username'] ?><br><br>
                <a href="<?= $base_url ?>">
                  return to <?= $base_url ?>
                </a>
              </p>

            <?php endif ?>
          <?php endif ?>

        </div>
        <div class="col-md-4"></div>
      </div>

    </div><!-- #container -->
  </body>
</html>
<?php
$_SESSION['username_inp'] = '';
$_SESSION['error'] = '';
?>
