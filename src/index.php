<?php
require 'config.php';

if (!empty($_SESSION['user_id'])) {
    header("Location: home.php");
} else {
    header("Location: login.php");
}
exit;
