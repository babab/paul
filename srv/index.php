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

require 'inc/springwhiz.php';
$tpl = new springwhiz;

$base_url = $tpl->get_base_url();
$content  = $tpl->get_content();
$q        = $tpl->get_q();

?><!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <script type="text/javascript" src="/js/lib/jquery-1.7.1.min.js"></script>
  </head>
  <body>
    <div id="container">
      <h1>springwhiz</h1>
      <p id="s0">enter query</p>
      <p id="s1">press enter to submit</p>
      <form method="get" action="<?php echo $base_url ?>/query.php">
        <input type="text" id="q" name="q" value="<?php echo $q ?>">
      </form>
      <br>

      <div id="menu">
        <small>type '@help' to get started</small>
      </div><!-- #menu -->

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
        $("#content").show();
        $("#menu").hide();
      </script>
    <?php endif ?>

  </body>
</html>
