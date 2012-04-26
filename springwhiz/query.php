<?php
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

require_once 'inc/lib/sprwz.php';
require_once 'inc/lib/user.php';
require_once 'inc/bookmark.php';
require_once 'inc/notepad.php';

class Query extends sprwz
{
    private $query;

    public function __construct()
    {
        parent::__construct();
        session_start();

        if (!empty($_POST) && !empty($_GET['m'])) {
            switch ($_GET['m']) {
            case 'user':
                $user = new user;
                $user->authenticate_form();
                header("Location: $this->base_url");
                exit;
            case 'notepad':
                $np = new notepad($_SESSION['username']);
                $np->authenticate_form();
                $loc = "$this->base_url/?cmd=notepad";
                header("Location: $loc");
                exit;
            }
        }

        $this->query = urldecode(trim($_GET['q']));

        if ($query = $this->handle()) {
            $url = $this->search_engine_url . $query;
            header("Location: $url");
            exit;
        }
        else {
            header("Location: $this->base_url");
            exit;
        }
    }

    public function handle()
    {
        if ($cmd = $this->command()) {
            $location = sprintf("%s/?cmd=%s", $this->base_url, $cmd);
            header("Location: $location");
            exit;
        }
        else if ($bm_str = $this->bookmark()) {
            if (!isset($_SESSION['logged_in'])
                    || $_SESSION['logged_in'] == false) {
                $_SESSION['error'] = 'You have to be registered and logged in '
                            . 'for using bookmarks';
                return false;
            }

            $bm = new bookmark;
            if (!$bm->gotoIfFound($_SESSION['username'], $bm_str)) {
                $_SESSION['error'] = 'That bookmark does not exist';
                header("Location: $this->base_url/");
                exit;
            }
        }
        else
            return $this->query;
    }

    public function command()
    {
        $command = substr($this->query, 1);

        if ($this->hasPrefix($this->prefix_command))
            if (!empty($command))
                return $command;
            else
                return 'unknown';
        else
            return false;
    }

    public function bookmark()
    {
        $bookmark = substr($this->query, 1);

        if ($this->hasPrefix($this->prefix_bookmark)
                && !empty($bookmark))
            return $bookmark;
        else
            return false;
    }

    private function hasPrefix($prefix)
    {
        return substr($this->query, 0, 1) == $prefix;
    }
}

$q = new Query();

