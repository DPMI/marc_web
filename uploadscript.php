<?php
require("sessionCheck.php");
require("config.php");

print "action = " . $HTTP_POST_VARS["action1"] . "eol <br>\n";
if ($HTTP_POST_VARS["action1"]==1) {
if (!isset($HTTP_POST_FILES['file'])) exit;
?>
    <html>
    <head>
    <title>File Upload Results</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>

<?
print $pageStyle;
?>

    <p><font face="Arial, Helvetica, sans-serif"><font size="+1">File Upload Results</font><br><br>

<?
    if (is_uploaded_file($HTTP_POST_FILES['file']['tmp_name'])) {
       	if ($HTTP_POST_FILES['file']['size']>$max_size) { print "The file is to big...<br>\n"; exit; }
       	if (file_exists($path . $HTTP_POST_FILES['file']['name'])) { print "File already exists<br>\n"; exit;}
       	$res=copy($HTTP_POST_FILES['file']['tmp_name'], $path . $HTTP_POST_FILES['file']['name']);
	if (!$res) { print "Upload failed.<br>\n"; exit; } else { print "Upload sucessfull. <br>\n"; }
       	$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
 	mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

 	$sql_update="INSERT files SET username='". $_SESSION["username"] ."', accesslevel='" . $HTTP_POST_VARS["accesslevel"] ."', filename='" . $HTTP_POST_FILES['file']['name'] ."', filesize='" . $HTTP_POST_FILES['file']['size'] ."', description='" .$HTTP_POST_VARS["description"] . "'";
 	print "sql_update = $sql_update <br>\n";

 	$result=mysqli_query($sql_update);
 	if(!$result) { print "MySQL error: " . mysqli_error(); exit; }
    } else {
    	print "file not uploaded.<br>\n";
    }

phpinfo();
?>
    <br><a href="uploadscript.php">Back</a>
    </font></p>
    </body>
    </html>

<?
} else {  // No file were uploaded. Lets print the form.
?>
    <html>
    <head>
    <title>File Upload</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>

    <body bgcolor="#FFFFFF" text="#000000">
    <p><font face="Arial, Helvetica, sans-serif"><font size="+1">File Upload</font><br><br>
 If your browser is upload-enabled, you will see &quot;Browse&quot;
(Netscape, Internet Explorer) or &quot;. . .&quot; (Opera) buttons below.
Use them to select file(s) to upload, then click the &quot;Upload&quot;
button. After the files have been uploaded, you will see a results screen.<br>

    <form method="post" enctype="multipart/form-data" action="uploadscript.php?SID=<? print $sid; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value='<? print $max_size; ?>'>
    <input type="hidden" name="action1" value="1">
    <table>
    <tr><td>File</td><td><input type="file" name="file"></td></tr>
    <tr><td>Description</td><td><textarea name="description" row=5 cols=60></textarea></td></tr>
    <tr><td>Access Level</td><td><select name = "accesslevel" >
	<option value="0"<? if ($row["accesslevel"]==0) { print " selected ";} ?>>Public</option>
	<option value="1"<? if ($row["accesslevel"]==1) { print " selected ";} ?>>Members</option>
	<option value="2"<? if ($row["accesslevel"]==2) { print " selected ";} ?>>Admin</option>
    </select></td></tr>


    <tr><td colspan=2><input type="submit" value="Upload"></td></tr>
    </form>
    </font></p>
    </body>
    </html>

<?
}
?>