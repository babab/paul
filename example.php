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

if (!isset($_SESSION['paul']['logged_in']) || !$_SESSION['paul']['logged_in']) {
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
    <meta charset="utf-8">
  </head>
  <body>
    <div>
      <?php if (isset($_SESSION['paul']['logged_in']) && $_SESSION['paul']['logged_in']): ?>

        logged in as
        <strong><?= $_SESSION['paul']['username'] ?></strong>

        <?php if (isset($_SESSION['paul']['last_seen'])): ?>
          | last login:
          <?php echo date("Y-m-d H:i:s", $_SESSION['paul']['last_seen']) ?>
        <?php endif ?>

        | <a href="<?= $base_url ?>paul/auth.php?logout">logout</a>

      <?php else: ?>

        not logged in
        | <a href="<?= $base_url ?>example.php?cmd=login">login</a>
        | <a href="<?= $base_url ?>example.php?cmd=register">register</a>

      <?php endif ?>
    </div>

    <?php if (isset($_SESSION['paul']['error']) && !empty($_SESSION['paul']['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['paul']['error'] ?></div>
    <?php endif ?>

    <?php if (isset($cmd) && ($cmd == 'login' || $cmd == 'register')): ?>
      <?php if (!isset($_SESSION['paul']['logged_in']) || !$_SESSION['paul']['logged_in']): ?>

        <form method="post" action="<?= $base_url ?>paul/auth.php">
          <input type="hidden" id="token" name="token"
                 value="<?= $token ?>">
          <ul style="list-style: none; line-height: 40px">
            <li>
              <label for="username">username</label>
              <?php if (!empty($_SESSION['paul']['username_inp'])): ?>
                <input type="text" id="username" name="username"
                       placeholder="username"
                       value="<?= $_SESSION['paul']['username_inp'] ?>">
              <?php else: ?>
                <input type="text" id="username" name="username"
                       placeholder="username">
              <?php endif ?>
            </li>
            <li>
              <label for="password">password</label>
              <input type="password" id="password" name="password"
                     class="form-control" placeholder="password">
            </li>
            <?php if ($cmd == 'register'): ?>
              <li>
                <label for="password2">password (again)</label>
                <input type="password" id="password2" name="password2"
                       class="form-control" placeholder="password (again)">
              </li>
            <?php else: ?>
              <li>
                <label>
                  <input type="checkbox" id="remember_me"
                         name="remember_me"> Remember me
                </label>
              </li>
            <?php endif ?>
            <li>
              <button type="submit" class="btn btn-default">
                <?= $cmd ?>
              </button>
            </li>
          </ul>
        </form>
      <?php else: ?>

        <p>
          you are already logged in as
          <?= $_SESSION['paul']['username'] ?><br><br>
          <a href="<?= $base_url ?>">
            return to <?= $base_url ?>
          </a>
        </p>

      <?php endif ?>
    <?php endif ?>
  </body>
</html>
<?php
$_SESSION['paul']['username_inp'] = '';
$_SESSION['paul']['error'] = '';
?>
