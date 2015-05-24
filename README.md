marc_web
--------

This assumes that the files are placed where your webserver can access them.

0. Create mysql user and appropriate database.
1. Create a config.local.php (cp config.local.php.sample config.local.php), and add the corresponding information.
1. Install/update the database  
   `cd migrations;  php pupdate_database.php [USERNAME]`
2. Configure the web site, ((via config.local.php, see instructions in file)).
3. Access the web site, and fix the last things that it complains about.  
   **Make sure to visit *"daemon status"* for full diagnostics.**
