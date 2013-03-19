marc_web
--------

This assumes that the files are placed where your webserver can access them.

1. Install the database  
   `mysql -u root -p <DATABASE> < install/mysql.sql`
2. Patch the tables, for each file in upgrade run  
   `php <file>`  
   run it in order, i.e. 0.7.1 then 0.7.2, then ...
3. Configure the web site, via config.local.php, see instructions in file.
4. Access the web site, and fix the last things that it complains about.  
   **Make sure to visit *"daemon status"* for full diagnostics.**
