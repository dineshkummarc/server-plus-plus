About Server++
==============
SERVER++ is a PHP based open source software. The script is designed focussing
on minimun memory usage and an attractive user interface. Server++ uses Google
Chart API to provide server information in an easy to understand and an 
enhanced way. It gives a comprehensive approach to the important server 
information like memory, swap, CPU load and disk usage while keeping an eye on
their normal functioning as well. It provides an alert service via both email 
and sms channel whenever CPU or swap usage increases. Server++ is recommended 
for anyone who wants to monitor their server live.

The project  Server++ was registered on SourceForge.net on June 15,2010 .

Features
=========
- exhaustive information about disk usage, CPU load, memory and swap
- lists top processes i.e processes consuming major CPU memory
- employs varied form of graphs like bar graphs, pie charts and timeline graphs
- timeline graphs can show statistics from last 1 sec to 1 year in a line chart format
- list out all Apache and Mysql processes
- shows the process tree and all network statistics

Documentation
============= 
The Main Scripts:
logger.php - executes linux commands used to monitor the server regularly.(interval set by crontab/cronjob).
extract.php - retrieves server configuration,version and other live data.
index.php - displays the Server ++ pannel for monitoring the server.
 
Installation
============= 
1.Download the files to your workstation and extract the archive to the required folder on the server
  for example: /path/to/server++
2.Give config.php file read, write, and execute permissions (777) .
(You can use Cpanel, Command line, or any FTP client, for the same.)
Eg: In the command line type in:
~/# chmod 777 /path/to/server++/config.php
3. Run install.php in the web browser and fill in the required details.
Eg: http://yourdomain/path/to/server++/install.php
4.Set the cronjob for  "logger.php" , it is required to execute within every 5 minutes (recommended; you can change the time interval as required.) for monitoring the server continuously.   
on the command line type in:
~/# crontab -e
<to edit the crontab> select the preffered editor <if you are editing crontab for the first time> and insert the following line:
5 * * * * php /path/to/server++/logger.php
Note: to execute the above command you should have the php-cgi/php-cli packages installed on the server.
The above command is recommended.  If you want to change the time interval from 5 minutes to 3 minutes or to every 1 minute you can replace the above line by
3 * * * * php /path/to/server++/logger.php
                           <or>
* * * * * php /path/to/server++/logger.php
5. The installation process of Server ++is complete.
6. After installation, give config.php  read permissions(444).
Eg:~/# chmod 444 /path/to/server++/config.php

You can start monitoring the server from:
http://yourdomain/path/to/server++/

Members
=======
- Kanika Goyal 					2nd year,COE     Delhi Technological University(Formerly DCE)
- Mayank Gupta                  2nd year,COE     Delhi Technological University(Formerly DCE)
- Shivani Sharma                2nd year,COE     Delhi Technological University(Formerly DCE)
- Sneha Mazumdar                2nd year,COE     Delhi Technological University(Formerly DCE)
- Sunakshi Bansal               2nd year,COE     Delhi Technological University(Formerly DCE)
- Syed Aamir Aarfi              4th year,ECE     Delhi Technological University(Formerly DCE)
- Shashwat Srivastava           4th year,ECE     Delhi Technological University(Formerly DCE)