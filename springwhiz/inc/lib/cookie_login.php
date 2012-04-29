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

class cookie_login extends sprwz
{
    private $username;

    public function __construct($username)
    {
        parent::__construct();
        $this->username = $username;
    }

    public function destroy()
    {
        $cookie = $this->_parse_cookie();
        $q = "DELETE FROM _T_cookie_login
                WHERE username = '".$this->db->escape($this->username)."'
                AND token = '".$this->db->escape($cookie[1])."' LIMIT 1";
        $this->db->query($q);
        setcookie('persistent_login_token', '', time() - 3600);
    }

    public function destroy_all()
    {
        $q = "DELETE FROM _T_cookie_login
                WHERE username = '".$this->db->escape($this->username)."'";
        $this->db->query($q);
        setcookie('persistent_login_token', '', time() - 3600);
    }

    public function assign()
    {
        $token = $this->_create_token();
        setcookie('persistent_login_token', $token . $this->username,
                time() + 60*60*24*30);

        $q = "INSERT INTO _T_cookie_login (username ,token)
                VALUES (
                    '".$this->db->escape($this->username)."',
                    '".$this->db->escape($token)."'
                )";
        $this->db->query($q);
    }

    public function authorize()
    {
        if (!$cookie = $this->_parse_cookie())
            return false;

        $this->username = $cookie[0];

        $tokens = $this->_fetch_tokens();
        foreach ($tokens as $token) {
            if ($token['token'] === $cookie[1]) {
                $_SESSION['username'] = $this->username;
                $_SESSION['logged_in'] = true;
                $this->destroy();
                $this->assign();
                return true;
            }
        }
    }

    private function _fetch_tokens()
    {
        $q = "SELECT token FROM _T_cookie_login "
            . "WHERE username = '$this->username'";
        return $this->db->qfetch($q);
    }

    private function _create_token()
    {
        return hash('sha256', mt_rand() . $this->secret_key);
    }

    private function _parse_cookie()
    {
        if (!isset($_COOKIE['persistent_login_token']))
            return false;

        $cookie = $_COOKIE['persistent_login_token'];
        $token = substr($cookie, 0, 64);
        $username = substr($cookie, 64);

        if (empty($username))
            return false;

        return array($username, $token);
    }

    public function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS _T_cookie_login (
                username        VARCHAR(100) NOT NULL,
                token           VARCHAR(74) NOT NULL UNIQUE
            ) ENGINE = InnoDB"
        );
    }
}
