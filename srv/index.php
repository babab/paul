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

$base_url   = $tpl->get_base_url();
$cmd        = $tpl->get_cmd();
$content    = $tpl->get_content();
$q          = $tpl->get_q();
$csrf_token = $tpl->get_csrf_token();

?><!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <script type="text/javascript" src="/js/lib/jquery-1.7.1.min.js"></script>
  </head>
  <body>
    <div id="container">
      <h1><a href="<?php echo $base_url ?>/">springwhiz</a></h1>

      <?php if (!isset($cmd) || $cmd != 'login'): ?>
        <p id="s0">enter query</p>
      <?php endif ?>

      <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <p>
          <span class="warn">Warning </span><?php echo $_SESSION['error'] ?>
          <br>
        </p>
      <?php endif ?>

      <p id="s1">press enter to submit</p>

      <?php if (isset($cmd) && ($cmd == 'login' || $cmd == 'register')): ?>
        <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
          <form method="post"
                action="<?php echo $base_url ?>/query.php">
            username<br>
            <?php if (!empty($_SESSION['username_inp'])): ?>
              <input type="text" id="username" name="username"
                     value="<?php echo $_SESSION['username_inp'] ?>"><br><br>
            <?php else: ?>
              <input type="text" id="username" name="username"><br><br>
            <?php endif ?>
            password<br>
            <input type="password" id="password" name="password"><br><br>
            <?php if ($cmd == 'register'): ?>
              password again<br>
              <input type="password" id="password2" name="password2"><br><br>
            <?php endif ?>
            <input type="hidden" id="csrf_token" name="csrf_token"
                   value="<?php echo $csrf_token ?>">
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
          <input type="text" id="q" name="q" value="<?php echo $q ?>">
          <input type="hidden" id="csrf_token" name="csrf_token"
                 value="<?php echo $csrf_token ?>">
      <?php endif ?>
      </form>
      <br>

      <div id="menu">
        <small>type '@help' to get started</small><br>
      </div>

      <div id="logindetails">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
          <small>logged in as <?php echo $_SESSION['username'] ?></small>
        <?php else: ?>
          <small>not logged in</small>
        <?php endif ?>
      </div>

      <div id="content">
        <?php echo $content ?>
      </div><!-- #content -->
      <br>
      <div id="footer">
        <a href="http://code.babab.nl/springwhiz/">springwhiz v0.1</a>
      </div><!-- #footer -->
    </div><!-- #container -->

    <script type="text/javascript" src="/js/main.js"></script>
    <?php if (!empty($content)): ?>
      <script type="text/javascript">
        $(document).ready(function(){
          $("#content").show();
          $("#menu").hide();
        });
      </script>
    <?php endif ?>

    <?php if (isset($cmd) && $cmd == 'login'): ?>
      <script type="text/javascript">
        $(document).ready(function(){
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
