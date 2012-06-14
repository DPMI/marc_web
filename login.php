<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="http://www.bth.se/bth/styles/bth.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Login</title>
  </head>

  <body class="bthcss">
    <h1>Login</h1>
    <p>This site uses cookies and sessions.</p>

    <div style="margin: auto; width: 260px; background: green; border: 1px solid black;">
      <form action="loginVerification.php" method="post">
	 <input type="hidden" name="return" value="<?=$_GET['return']?>" />
	<table width="100%">
	  <tr>
	    <td>User Name</td>
	    <td><input type="text" name="uName" /></td>
	  </tr>
	  <tr>
	    <td>Password</td>
	    <td><input type="password" name="pWord" /></td>
	  </tr>
	  <tr>
	    <td colspan="2" style="text-align: center;">
	      <input type="submit" value="Enter" />
	    </td>
	  </tr>
	</table>
      </form>
    </div>

  </body>
</html>
