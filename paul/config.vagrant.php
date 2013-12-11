<?php $paul_conf = array
(
    # Set this to the parent path where the paul directory can be found
    'base_url'   => 'http://localhost:8080/',

    # Redirecting
    'redirect_after_login'  => 'http://localhost:8080/example.php',
    'redirect_after_logout' => 'http://localhost:8080/example.php',

    # Change this to something else
    'secret_key' => '0PR_]DJcPRB:+}rZ:B_Y)9;,:)):nD].10b7pVw?s$khy?]BdS',

    # Database settings
    'db_host'    => 'localhost',
    'db_port'    => '3306',
    'db_name'    => 'paul',
    'db_user'    => 'root',
    'db_pass'    => '',
    'db_prefix'  => 'paul_',
)
?>
