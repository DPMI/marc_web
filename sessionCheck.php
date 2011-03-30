<?
session_start();
// Use $HTTP_SESSION_VARS with PHP 4.0.6 or less
$noSESSION=0;
if (!session_is_registered("OK")) {
//    header("Location: loginDenied.php");
$SESSION=0;
} else {
//    print "OK";
$SESSION=1;

}
?>
