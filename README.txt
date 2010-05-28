+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Agate CMS v0.1 by Abhishek Shrivastava (abhishekdelta) [i.abhi27@gmail.com]
===========================================================================


+INSTALLATION INSTRUCTIONS+
---------------------------
1) Extract the contents of the .zip file to your webserver's www folder.

2) If you're using Linux, make sure you give enough permissions to the folder, as of now (just for installation), you can give 777 permissions by the following command : 

>chmod -R 777 agatecms/

3) You must have a MySQL Database for the Agate CMS to store its information. It's better if you also create a new user in your MySQL Database, and also create a new database in it and give your new user all permissions over that new database. If you're using PHPMyAdmin, you can do the following :

	Login As ROOT > Goto Privileges Tab > Add New User > Enter the following information :
	
	Username : agatecms <or chose your own username>
	Host : %
	Password & Re-Type : passwd <or chose your own password>
	
	Then Select the radio button below with the label "Create database with same name and grant all privileges".
	Then click on the "GO" button.

4) If you were not able to execute the above steps or if you don't have PHPMyAdmin, you can do the following : 

	If you're using Linux system, open terminal and type :
	
	>mysql -u root -p
	>Enter Password : <enter-your-root-password-of-mysql>
	
	mysql> CREATE USER 'agatecms'@'%' IDENTIFIED BY 'passwd';
	mysql> GRANT USAGE ON * . * TO 'agatecms'@'%' IDENTIFIED BY 'passwd' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
	mysql> CREATE DATABASE IF NOT EXISTS `agatecmsdb` ;
	mysql> GRANT ALL PRIVILEGES ON `agatecmsdb` . * TO 'agatecms'@'%';
	
	Now you have created a database with name "agatecmsdb" and also a user "agatecms" with password "passwd" and full permissions over database "agatecms".
	
5) Next, open your browser and point to http://localhost/agatecms/install

6) Now, enter the following details : 

Database Server : localhost
Database Name :	agatecmsdb
Database User :	agatecms
Database Password : passwd	
Database Password (Verify) : passwd
Database Prefix (Optional) : v0_ 

The Database Prefix is optional, you may leave it blank also. Click on Next and follow the next instructions as mentioned in the page until you finish "Step 3" of Installation.

7) If the installation was successful, you can see your website now :)
	
	



