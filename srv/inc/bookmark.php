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

class bookmark {

    private $db;

    public function __construct()
    {
        $this->db = new dbhandler;
    }

    public function add($username, $label, $url)
    {
        $this->username = $username;

        if ($this->fetch($username, $label)) {
            $_SESSION['error'] = 'A bookmark for this label already exists.';
            return false;
        }

        $user = new user;
        $user_id = $user->id($username);

        $q = "INSERT INTO _T_bookmarks (user_id, label, url)
            VALUES (
                '".$this->db->escape($user_id)."',
                '".$this->db->escape($label)."',
                '".$this->db->escape($url)."'
            )";
        $this->db->query($q);
        return true;
    }

    public function fetch($username, $label)
    {
        $q = "SELECT url FROM _T_users "
            . "WHERE username = '$username'"
            . "AND label = $label";
        return $this->db->qfetch_first($q);
    }

    public function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS _T_bookmarks (
                bookmark_id     INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id         INT(10) NOT NULL UNIQUE,
                label           VARCHAR(100) NOT NULL,
                url             VARCHAR(4096) NOT NULL
            ) ENGINE = InnoDB");

        $this->db->query("
            ALTER TABLE _T_bookmarks ADD FOREIGN KEY (user_id)
            REFERENCES _T_users(user_id)
            ON DELETE CASCADE ON UPDATE NO ACTION");
    }
}
