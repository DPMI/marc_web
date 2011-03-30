<?
session_start();
// Use $HTTP_SESSION_VARS with PHP 4.0.6 or less
$noSESSION=0;
$SESSION = isset($_SESSION['OK']) && $_SESSION['OK'] == 'OK';

?>
