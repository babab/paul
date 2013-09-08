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

require_once 'inc/lib/paul.php';
require_once 'inc/lib/cookie_login.php';
require_once 'inc/command.php';

class springwhiz extends paul
{
    private $command;

    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $cookie = new cookie_login(null);
            $cookie->authorize();
        }
    }

    public function get_base_url()
    {
        return $this->base_url;
    }

    private function _parseCommand()
    {
        if (!$this->command) {
            if (!empty($_GET['cmd']))
                $this->command = command::getContent(
                        htmlentities($_GET['cmd']));
            else
                $this->command = command::getContent(htmlentities(''));
        }
    }

    public function get_cmd()
    {
        $this->_parseCommand();
        return $this->command[0];
    }

    public function get_content()
    {
        $this->_parseCommand();
        return $this->command[1];
    }

    public function get_q()
    {
        if (!empty($_GET['q']))
            return htmlentities($_GET['q']);
        else
            return '';
    }

    public function get_footer()
    {
        return $this->footer;
    }

    public function get_prefix_command()
    {
        return $this->prefix_command;
    }

    public function get_prefix_bookmark()
    {
        return $this->prefix_bookmark;
    }
}
