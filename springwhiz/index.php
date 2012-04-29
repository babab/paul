<?php
# vim: set ts=2 sw=2 sts=2:
#
# Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>
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

require 'inc/springwhiz.php';
$tpl = new springwhiz;

$base_url         = $tpl->get_base_url();
$cmd              = $tpl->get_cmd();
$content          = $tpl->get_content();
$q                = $tpl->get_q();
$footer           = $tpl->get_footer();
$token            = $tpl->create_token();
$prefix_command   = $tpl->get_prefix_command();
$prefix_bookmark  = $tpl->get_prefix_bookmark();

?><!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css"
          href="<?php echo $base_url ?>/css/main.css">
    <script type="text/javascript"
            src="<?php echo $base_url ?>/js/lib/jquery-1.7.1.min.js">
    </script>
  </head>
  <body>
    <div id="container">
      <div id="header"><a href="<?php echo $base_url ?>/">springwhiz</a></div>

      <div id="topmenu">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
          <small>
          <a href="<?php echo $base_url ?>/?cmd=bookmark list">bookmarks</a>
          | <a href="<?php echo $base_url ?>/?cmd=notepad">notepad</a>
          | <a href="<?php echo $base_url ?>/?cmd=logout">logout</a>
          </small>
        <?php else: ?>
          <small>
            not logged in
            | <a href="<?php echo $base_url ?>/?cmd=login">login</a>
          </small>
        <?php endif ?>
        <small> | <a href="<?php echo $base_url ?>/?cmd=help">help</a></small>
        <br><br>
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
                action="<?php echo $base_url ?>/query.php?m=user">
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
            <a href="<?php echo $base_url ?>/">
              return to <?php echo $base_url ?>/
            </a>
          </p>
        <?php endif ?>
      <?php else: ?>
        <form method="get"
              action="<?php echo $base_url ?>/query.php">
          <input type="text" id="q" name="q"
                 autocomplete="off"
                 placeholder="enter search string or command"
                 value="<?php echo $q ?>">
      <?php endif ?>
      </form>
      <br>

      <div id="menu">
        type '@help' to get started
      </div>
      <br>

      <div id="content">
        <?php echo $content ?>
      </div><!-- #content -->
      <br>
      <div id="footer">
        <a href="https://github.com/babab/springwhiz/">springwhiz v0.1</a>
        <?php if ($footer) echo "| $footer"; ?> |
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
          logged in as <?php echo $_SESSION['username'] ?>
        <?php else: ?>
          not logged in
        <?php endif ?>
      </div><!-- #footer -->
    </div><!-- #container -->

    <script type="text/javascript">
      <?php
        echo "sprwz_prefix_command = '$prefix_command';\n";
        echo "sprwz_prefix_bookmark = '$prefix_bookmark';\n";
      ?>
    </script>
    <script type="text/javascript"
            src="<?php echo $base_url ?>/js/main.js">
    </script>
    <?php if (!empty($content)): ?>
      <script type="text/javascript">
        $(document).ready(function(){
          $("#content").show();
        });
      </script>
    <?php endif ?>

    <?php if (isset($cmd) && ($cmd == 'login' || $cmd == 'register')): ?>
      <script type="text/javascript">
        $(document).ready(function(){
          $("#menu").hide();
          <?php if (empty($_SESSION['username_inp'])): ?>
            $("#username").focus();
          <?php else: ?>
            $("#password").focus();
          <?php endif ?>
        });
      </script>
    <?php endif ?>

  </body>
</html>
<?php
$_SESSION['username_inp'] = '';
$_SESSION['error'] = '';
?>
