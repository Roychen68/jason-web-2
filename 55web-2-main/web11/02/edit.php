<?php
include "db.php";

$conn->query("UPDATE `tickets` SET `firstname`='{$_POST['firstname']}',`lastname`='{$_POST['lastname']}',`phone`='{$_POST['phone']}',`password`='{$_POST['password']}' WHERE `id`='{$_POST['id']}'");
?>