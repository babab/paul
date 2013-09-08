<?php
/*
 * Copyright (c) 2012, 2013  Benjamin Althues <benjamin@babab.nl>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

require_once 'inc/lib/dbhandler.php';

class paul {
    public static $settings = array(
            'base_url',
            'secret_key',
            'prefix_command',
            'prefix_bookmark',
            'search_engine_url',
            );
    public static $settings_db = array(
            'db_host',
            'db_port',
            'db_name',
            'db_user',
            'db_pass',
            'db_prefix',
            );
    public static $settings_optional = array(
            'footer',
            );

    public $base_url;
    protected $secret_key;
    protected $prefix_command;
    protected $prefix_bookmark;
    protected $search_engine_url;

    protected $footer;

    protected $db;

    public function __construct()
    {
        global $paul_conf;
        include_once 'config.php';

        if (isset($paul_conf))
            $conf = $paul_conf;
        else
            $conf = null;

        if (empty($conf))
            $this->error("Could not load config file. Please copy "
                    . "'config.example.php' to 'config.php' and edit it.");

        foreach (self::$settings as $s) {
            $this->$s = $conf[$s];

            if (empty($this->$s))
                self::error("Could not load the $s setting from config file.");
        }

        foreach (self::$settings_optional as $s) {
            $this->$s = $conf[$s];
        }

        $db_login = array();
        foreach (self::$settings_db as $s) {
            $db_login[$s] = $conf[$s];

            if (empty($db_login[$s]))
                self::error("Could not load the $s setting from config file.");
        }

        if (!$this->db)
            $this->db = new dbhandler($db_login['db_name'],
                    $db_login['db_user'], $db_login['db_pass'],
                    $db_login['db_prefix'], $db_login['db_host'],
                    $db_login['db_port']);
    }

    public function create_token()
    {
        $token = md5($this->secret_key . mt_rand());
        $_SESSION['token'] = $token;
        return $token;
    }

    protected function get_token()
    {
        return $_SESSION['token'];
    }

    public static function error($errormsg)
    {
        die("<p><strong style=\"color:red\">paul error</strong> $errormsg");
    }


    public static function requireValidToken()
    {
        if ($_POST['token'] === $_SESSION['token']) {
            $_SESSION['token'] = '';
            return true;
        }
        self::error("Invalid token.");
    }
}
