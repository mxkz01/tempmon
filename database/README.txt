=== Database ===

This is a structural copy of the MySQL Database.

You will need to create a MySQL Database and a MySQL user (we used tempdb for both) and assign all permissions (you can probably narrow down the permissions if you'd like once it's all working).

We have been managing SQL Data via phpMyAdmin which seems to be the easiest way to get things up and running. Once you've created the database, user and assigned permissions, you can use the Import feature from within the database to import the database structure from the .sql file.

We haven't included the create database and drop table commands in the SQL, it's designed for importing into a blank database to provide the table structure only.

