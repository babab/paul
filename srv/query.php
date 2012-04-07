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

$sprwz_conf = parse_ini_file('../config', true);

require_once 'bookmark.php';

if (empty($sprwz_conf['main']['base_url']))
    die("query.php could not load the base_url setting");
if (empty($sprwz_conf['core']['prefix_command']))
    die("query.php could not load the prefix_command setting");
if (empty($sprwz_conf['core']['prefix_bookmark']))
    die("query.php could not load the prefix_bookmark setting");

class Query
{
    private $query;
    private $redirectOnInit;
    private $conf;

    public function __construct($auto_redirect=false)
    {
        global $sprwz_conf;

        $this->conf = $sprwz_conf;
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
                    $this->conf['main']['base_url'], $cmd, $this->query);
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
            header('Location: ' . $this->conf['main']['base_url']);

            if ($exit_after_redirect)
                exit;
        }
    }

    public function command()
    {
        $command = substr($this->query, 1);

        if ($this->hasPrefix($this->conf['core']['prefix_command']))
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

        if ($this->hasPrefix($this->conf['core']['prefix_bookmark'])
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

