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
require_once 'inc/lib/dbhandler.php';
require_once 'inc/bookmark.php';

class Query extends sprwz
{
    private $query;
    private $redirectOnInit;
    private $conf;

    public function __construct($auto_redirect=false)
    {
        parent::__construct();

        $this->query = urldecode(trim($_GET['q']));
        $this->redirectOnInit = $auto_redirect;

        if ($auto_redirect)
            $this->redirectIfEmpty();
    }

    public function handle()
    {
        if (!$this->redirectOnInit)
            $this->redirectIfEmpty();

        if ($cmd = $this->command()) {
            $location = sprintf("%s/?cmd=%s&q=%s",
                    $this->base_url, $cmd, $this->query);
            header("Location: $location");
            exit;
        }
        else if ($bm_str = $this->bookmark()) {
          $bm = new bookmark($bm_str);
        }
        else
            return $this->query;
    }

    public function redirectIfEmpty($exit_after_redirect=true)
    {
        if (empty($this->query)) {
            header('Location: ' . $this->base_url);

            if ($exit_after_redirect)
                exit;
        }
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

if ($query = $q->handle())
    header("Location: https://duckduckgo.com/?q=" . $query);

