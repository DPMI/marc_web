marc_web
--------

Requirements (Ubuntu 20.04 LTS)
[may be incomplete, and will also help with marcd build]

apt-get install apache2 mysql-server libapache2-mod-php phpmyadmin rrdtool librrd-dev libmysqlclient-dev pkg-config autogen libtool automake autoconf build-essential


Update your php.ini, so that 'short_on_tag == true'. 

Principles.
Create /var/lib/marc, /var/www/marc_web/cache
Create a user marc, and add marc to the www-data group.
Make both folders read (and write?) by www-data.

mkdir /var/lib/marc
mkdir /var/www/marc_web/cache
groupadd marc
addgroup www-data marc
/var/www/marc_web/cache -- Readable by www-data
/var/lib/mard -- Readable by www-data
chgrp marc /var/www/marc_web/cache/
chmod g+w /var/www/marc_web/cache/



Install

This assumes that the files are placed where your webserver can access them.

1. Install the database  
   `mysql -u root -p <DATABASE> < install/mysql.sql`
2. Patch the tables, for each file in upgrade run  
   `php <file>`  
   run it in order, i.e. 0.7.1 then 0.7.2, then ...
3. Configure the web site, via config.local.php, see instructions in file.
4. Access the web site, and fix the last things that it complains about.  
   **Make sure to visit *"daemon status"* for full diagnostics.**



