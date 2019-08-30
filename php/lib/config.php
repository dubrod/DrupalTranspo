<?php
$prefix = "modxC4";

$mysqli = new mysqli("localhost", "my_user", "my_password", "db_name");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
} else {

}
?>
