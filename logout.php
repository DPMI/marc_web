<?php
require("sessionCheck.php");
require("config.php");
session_unset();
header("Location: index.html");
?>