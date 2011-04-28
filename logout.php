<?php
require("sessionCheck.php");
require("config.inc");
session_unset();
header("Location: index.html");
?>