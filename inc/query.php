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

require_once 'settings.php';

if (!defined('SITE_URL'))
    die("query.php could not load the SITE_URL setting");
if (!defined('PREFIX_COMMAND'))
    die("query.php could not load the PREFIX_COMMAND setting");
if (!defined('PREFIX_BOOKMARK'))
    die("query.php could not load the PREFIX_BOOKMARK setting");

class Query
{
    private $query;
    private $redirectOnInit;

    public function __construct($auto_redirect=false)
    {
        $this->query = urldecode(trim($_GET['q']));
        $this->redirectOnInit = $auto_redirect;

        if ($auto_redirect)
            $this->redirectIfEmpty();
    }

    public function qparse()
    {
        if (!$this->redirectOnInit)
            $this->redirectIfEmpty();

        if ($cmd = $this->command()) {
            $location = sprintf("%s/?cmd=%s&q=%s", SITE_URL,
                    $cmd, $this->query);
            header("Location: $location");
            exit;
        }
        else
            return $this->query;
    }

    public function redirectIfEmpty($exit_after_redirect=true)
    {
        if (empty($this->query)) {
            header('Location: ' . SITE_URL);

            if ($exit_after_redirect)
                exit;
        }
    }

    public function command()
    {
        $command = substr($this->query, 1);

        if ($this->hasPrefix(PREFIX_COMMAND))
            if (!empty($command))
                return $command;
            else
                return 'unknown';
        else
            return false;
    }

    private function hasPrefix($prefix)
    {
        return substr($this->query, 0, 1) == $prefix;
    }
}
