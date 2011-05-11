<?php
require("sessionCheck.php");
require("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Measurement point control</title>
    <style>
      #msgtable {
        float: left;
        margin-right: 2em;
      }
      #sendtable {
      
      }
    </style>
    <script type="text/javascript">
      function fill(id){
        document.getElementById('type').value = id;
        document.getElementById('message').focus();
      }
    </script>
  </head>
  
  <body class="bthcss">
    <div id="content">
      <table border="1" id="msgtable">
	<tr><th>Id</th><th>Type</th><th>Message</th></tr>
	<tr><td> 1</td><td><a href="javascript:fill( 1);">Authorize MP.</a></td><td>MPID</td></tr>
	<tr><td> 2</td><td><a href="javascript:fill( 2);">Reload filters</td><td>void</td></tr>
	<tr><td> 3</td><td><a href="javascript:fill( 3);">Get a new filter.</td><td>Filter Id </td></tr>
	<tr><td> 4</td><td><a href="javascript:fill( 4);">Change filter.</td><td>Filter Id</td></tr>
	<tr><td> 5</td><td><a href="javascript:fill( 5);">Drop filter.</td><td>Filter Id</td></tr>
	<tr><td> 6</td><td><a href="javascript:fill( 6);">Verify Filter.</td><td>Filter Id</td></tr>
	<tr><td> 7</td><td><a href="javascript:fill( 7);">Verify All Filter</td><td>void</td></tr>
	<tr><td> 8</td><td><a href="javascript:fill( 8);">Terminate MP</td><td>Magic word</td></tr>
	<tr><td> 9</td><td><a href="javascript:fill( 9);">Flush Consumer Buffers</td><td>void</td></tr>
	<tr><td>10</td><td><a href="javascript:fill(10);">Flush Consumer X Buffer</td><td>Consumer ID</td></tr>
      </table>
      
      <form action="sndMsg3.php?SID=<?=$sid?>&amp;id=<?=$_GET['id']?>" method="post">
	<table border="1" id="sendtable">
	  <tr>
	    <td style="background: #D3DCE3; text-align: right;">type</td>
	    <td><input name="type" id="type" type="text" size="3" maxlength="3" /></td>
	  </tr>
	  <tr>
	    <td style="background: #D3DCE3; text-align: right;">message</td>
	    <td><input name="message" id="message" type="text" maxlength="100" /></td>
	  </tr>
	  <tr>
	    <td><input type="submit" value="Send" /></td>
	    <td><input type="reset" value="Reset" /></td>
	  </tr>
	</table>
      </form>

      <br style="clear: both;"/>
    </div>
  </body>
</html>
