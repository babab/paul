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

final class user extends sprwz
{
    private $db;
    private $username;
    private $salt;
    private $password;

    public function __construct()
    {
        $this->db = new dbhandler;
    }

    public function add($username, $password)
    {
        $this->username = $username;
        $this->_makesalt();
        $this->_makepassword($password);

        if ($this->user_exists())
            return false;

        $q = "INSERT INTO _T_users (username, password, salt, last_seen)
                VALUES (
                    '$this->username',
                    '$this->password',
                    '$this->salt',
                    '" . time() . "'
                )";
        $this->db->query($q);
        return true;
    }

    public function authenticate_form()
    {
        if (empty($_POST))
            return false;

        if ($_SESSION['logged_in'])
            return false;

        $_SESSION['error'] = '';
        $_SESSION['logged_in'] = false;

        $this->username = filter_input(INPUT_POST, 'username',
                FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password',
                FILTER_SANITIZE_STRING);
        if (isset($_POST['password2'])) {
            $password2 = filter_input(INPUT_POST, 'password2',
                    FILTER_SANITIZE_STRING);

            if ($password !== $password2) {
                $_SESSION['error'] = 'Passwords do not match, please '
                        . 'try again.';
                $url = "$this->base_url/?cmd=register";
                header("Location: $url");
                exit;
            }

            if ($this->add($this->username, $password)) {
                $_SESSION['username'] = $this->username;
                $_SESSION['logged_in'] = true;
                return true;
            }
            else {
                $_SESSION['error'] = 'That username is already taken, please '
                        . 'try another one.';
                $_SESSION['username_inp'] = $this->username;
                $url = "$this->base_url/?cmd=register $this->username";
                header("Location: $url");
                exit;
            }
        }

        $this->_makesalt();
        $this->_makepassword($password);

        if ($user = $this->fetch_user()) {
            if ($user['password'] === $this->password) {
                $_SESSION['username'] = $this->username;
                $_SESSION['logged_in'] = true;
                return true;
            }
        }

        $_SESSION['error'] = 'Wrong username or password';
        return false;
    }

    public function fetch_user()
    {
        $q = "SELECT * FROM _T_users WHERE username = '$this->username'";
        return $this->db->qfetch_first($q);
    }

    public function user_exists()
    {
        $q = "SELECT last_seen FROM _T_users "
                . "WHERE username = '$this->username'";
        return $this->db->qfetch_first($q) !== false;
    }

    public function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS _T_users (
                user_id         INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                username        VARCHAR(100) NOT NULL,
                password        VARCHAR(512) NOT NULL,
                salt            VARCHAR(512) NOT NULL,
                last_seen       INT(10) NOT NULL
            ) ENGINE = InnoDB"
        );
    }

    private function _makesalt()
    {
        $this->salt = hash('sha512', $this->username . $this->base_url);
    }

    private function _makepassword($password)
    {
        $this->password = hash('sha512', $password . $this->salt);
    }
}
