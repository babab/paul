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
  <body>
    <div id="container">
      <div>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

          logged in as
          <strong><?php echo $_SESSION['username'] ?></strong>
          <?php if (isset($_SESSION['last_seen'])): ?>
            | last login:
            <?php echo date("Y-m-d H:i:s", $_SESSION['last_seen']) ?>
          <?php endif ?>
          | <a href="<?php echo $base_url ?>paul/auth.php?logout">logout</a>

        <?php else: ?>

          not logged in
          | <a href="<?php echo $base_url ?>example.php?cmd=login">login</a>
          | <a href="<?php echo $base_url ?>example.php?cmd=register">register</a>
        <?php endif ?>

      </div>

      <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <p>
          <span class="warn">Warning </span><?php echo $_SESSION['error'] ?>
          <br>
        </p>
      <?php endif ?>

      <?php if (isset($cmd) && ($cmd == 'login' || $cmd == 'register')): ?>
        <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>

          <form method="post"
                action="<?php echo $base_url ?>paul/auth.php">
            <?php if (!empty($_SESSION['username_inp'])): ?>
              <input type="text" id="username" name="username"
                     placeholder="username"
                     value="<?php echo $_SESSION['username_inp'] ?>"><br><br>
            <?php else: ?>
              <input type="text" id="username" name="username"
                     placeholder="username">
              <br><br>
            <?php endif ?>
            <input type="password" id="password" name="password"
                   placeholder="password">
            <br><br>
            <?php if ($cmd == 'register'): ?>
              <input type="password" id="password2" name="password2"
                   placeholder="password (check)">
              <br><br>
            <?php else: ?>
              <input type="checkbox" id="remember_me" name="remember_me">
              <label for="remember_me">remember me</label>
              <br><br>
            <?php endif ?>
            <input type="hidden" id="token" name="token"
                   value="<?php echo $token ?>">
            <input type="submit" id="submit" name="submit"
                   value="<?php echo $cmd ?>">

        <?php else: ?>

          <p>
            you are already logged in as
            <?php echo $_SESSION['username'] ?><br><br>
            <a href="<?php echo $base_url ?>">
              return to <?php echo $base_url ?>
            </a>
          </p>

        <?php endif ?>
      <?php endif ?>
      </form>
    </div><!-- #container -->
  </body>
</html>
<?php
$_SESSION['username_inp'] = '';
$_SESSION['error'] = '';
?>
