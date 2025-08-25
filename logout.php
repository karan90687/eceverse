<?php
session_start();
session_unset();
session_destroy();
header("Location: create_account.php");
exit;
