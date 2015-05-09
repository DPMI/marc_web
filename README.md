marc_web
--------

This assumes that the files are placed where your webserver can access them.

1. Install/update the database  
   `migrations/update_database.php [USERNAME]`
2. Configure the web site, via config.local.php, see instructions in file.
3. Access the web site, and fix the last things that it complains about.  
   **Make sure to visit *"daemon status"* for full diagnostics.**
