<?php
/*
 * Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>
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

class sprwz {
    public static $settings = array(
            'base_url',
            'prefix_command',
            'prefix_bookmark',
            'search_engine_url',
            'db_host',
            'db_port',
            'db_name',
            'db_user',
            'db_pass',
            'db_prefix',
            );

    protected $base_url;
    protected $prefix_command;
    protected $prefix_bookmark;
    protected $search_engine_url;

    protected $db_host;
    protected $db_port;
    protected $db_name;
    protected $db_user;
    protected $db_pass;
    protected $db_prefix;

    public function __construct()
    {
        $conf = parse_ini_file('../config', true);

        if (empty($conf))
            $this->error("Could not load config file. Please copy "
                    . "'config.example' to 'config' and edit it.");

        $this->base_url = $conf['main']['base_url'];
        $this->prefix_command = $conf['core']['prefix_command'];
        $this->prefix_bookmark = $conf['core']['prefix_bookmark'];
        $this->search_engine_url = $conf['core']['search_engine_url'];

        $this->db_host = $conf['db']['host'];
        $this->db_port = $conf['db']['port'];
        $this->db_name = $conf['db']['name'];
        $this->db_user = $conf['db']['user'];
        $this->db_pass = $conf['db']['pass'];
        $this->db_prefix = $conf['db']['prefix'];

        $this->checkconf();
    }

    public static function error($errormsg)
    {
        die("<p><strong style=\"color:red\">Error</strong> $errormsg");
    }

    public function checkconf()
    {
        foreach (self::$settings as $s) {
            if (empty($this->$s))
                self::error("Could not load the $s setting from config file.");
        }
    }
}
