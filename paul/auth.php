<?php
# Copyright (c) 2012, 2013, 2014 Benjamin Althues <benjamin@babab.nl>
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
require_once 'paul.php';
$user = new user;

if (isset($_GET['logout'])) {
    if (isset($_SESSION['paul']['logged_in'])
            && $_SESSION['paul']['logged_in']) {
        $cookie = new cookie_login($_SESSION['paul']['username']);

        if ($_GET['logout'] == 'all')
            $cookie->destroy_all();
        else
            $cookie->destroy();
    }
    $_SESSION['paul'] = array();
    header("Location: $user->redirect_after_logout");
    exit;
}

if (!empty($_POST)) {
    $user->authenticate_form();
    header("Location: $user->redirect_after_login");
    exit;
}
