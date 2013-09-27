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

class paul {
    public static $settings = array(
        'base_url',
        'redirect_after_login',
        'redirect_after_logout',
        'secret_key',
        'db_host',
        'db_port',
        'db_name',
        'db_user',
        'db_pass',
        'db_prefix',
    );

    public $base_url;
    protected $secret_key;
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

        if (!$this->db)
            $this->db = new dbhandler(
                $this->db_name,
                $this->db_user,
                $this->db_pass,
                $this->db_prefix,
                $this->db_host,
                $this->db_port
            );
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

class dbhandler {
    private $db_prefix;
    private $db_conn;
    private $db_query;
    private $db_res;

    public function __construct($db_name, $db_user, $db_pass, $db_prefix,
            $db_host = 'localhost', $db_port = 3306)
    {
        $this->db_prefix = $db_prefix;
        $this->db_conn = mysql_connect($db_host.':'.$db_port,
                                       $db_user, $db_pass);
        if (!$this->db_conn)
            paul::error("Connection error: ". mysql_error());
        if (!mysql_select_db($db_name, $this->db_conn)) {
            paul::error("Error connecting to database '" . $db_name .
                "': ". mysql_error());
        }
        return $this;
    }

    public function query($query)
    {
        $q = str_replace('_T_', $this->db_prefix, $query);
        if (!$this->db_query = mysql_query($q, $this->db_conn))
            paul::error("Query error: ". mysql_error());

        return $this;
    }

    public function fetch()
    {
        $this->db_res = array();

        if ($this->db_query) {
            while ($row = mysql_fetch_assoc($this->db_query))
                $this->db_res[] = $row;
        }

        if (!empty($this->db_res))
            return $this->db_res;
        else
            return false;
    }

    public function qfetch($query)
    {
        return $this->query($query)->fetch();
    }

    public function qfetch_first($query)
    {
        $rows = $this->query($query . ' LIMIT 1')->fetch();
        if (isset($rows[0]))
            return $rows[0];
        else
            return false;
    }

    public function escape($input)
    {
        return mysql_real_escape_string(strip_tags($input));
    }
}

final class user extends paul
{
    private $username;
    private $salt;
    private $password;

    public function __construct()
    {
        parent::__construct();
    }

    public function add($username, $password)
    {
        $this->username = $username;
        $this->_makesalt();
        $this->_makepassword($password);

        if ($this->user_exists())
            return false;

        $q = "INSERT INTO _T_users (username, password, salt,
                                    last_seen, last_ip)
                VALUES (
                    '".$this->db->escape($this->username)."',
                    '".$this->db->escape($this->password)."',
                    '".$this->db->escape($this->salt)."',
                    '" . time() . "',
                    '" . htmlentities($_SERVER['REMOTE_ADDR']) . "'
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

        $this->requireValidToken();

        $_SESSION['error'] = '';
        $_SESSION['logged_in'] = false;
        $_SESSION['logged_in_with_password'] = false;

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
                $_SESSION['logged_in_with_password'] = true;
                $_SESSION['last_seen'] = $user['last_seen'];
                $_SESSION['last_ip'] = $user['last_ip'];

                if (isset($_POST['remember_me'])) {
                    $cookie = new cookie_login($this->username);
                    $cookie->destroy();
                    $cookie->assign();
                }
                $this->update_last_login($this->username);
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

    public function id($username)
    {
        if (empty($username))
            return false;

        $q = "SELECT user_id FROM _T_users WHERE username = '$username'";
        if ($res = $this->db->qfetch_first($q))
            return (int) $res['user_id'];
    }

    public function user_exists()
    {
        $q = "SELECT last_seen FROM _T_users "
                . "WHERE username = '$this->username'";
        return $this->db->qfetch_first($q) !== false;
    }

    public function update_last_login($username)
    {
        $user_id = $this->id($username);
        $q = "UPDATE _T_users
                SET last_seen = '".time()."',
                last_ip = '".htmlentities($_SERVER['REMOTE_ADDR'])."'
                WHERE user_id = '$user_id'";
        $this->db->query($q);
    }

    public function process_last_login($username)
    {
        $user_id = $this->id($username);
        $q = "SELECT last_seen, last_ip FROM _T_users
                WHERE user_id = '$user_id'";
        if ($res = $this->db->qfetch_first($q)) {
            $_SESSION['last_seen'] = (int) $res['last_seen'];
            $_SESSION['last_ip'] = $res['last_ip'];
        }
    }

    public function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS _T_users (
                user_id         INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                username        VARCHAR(100) NOT NULL,
                password        VARCHAR(128) NOT NULL,
                salt            VARCHAR(128) NOT NULL,
                last_seen       INT(10) NOT NULL,
                last_ip         VARCHAR(70) NOT NULL
            ) ENGINE = InnoDB"
        );
    }

    private function _makesalt()
    {
        $this->salt = hash('sha512', $this->username . $this->secret_key);
    }

    private function _makepassword($password)
    {
        $this->password = hash('sha512', $password . $this->salt);
    }
}

final class cookie_login extends paul
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

                $user = new user;
                $user->process_last_login($this->username);
                $user->update_last_login($this->username);
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
