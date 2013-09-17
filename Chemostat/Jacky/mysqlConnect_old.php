<?php
mysql_connect("localhost", "root", "loonatic07") or die(mysql_error());
//Drop Database
//mysql_query("DROP DATABASE my_db");
//Create Database
//mysql_query("CREATE DATABASE my_db") or die(mysql_error());
//echo "Database Created<br />";
//Select Database
mysql_select_db("viral_dark_matter") or die(mysql_error());
//Create Tables
mysql_query("CREATE TABLE samplei_info(
reactor_ID VARCHAR(2),
sample_ID INT(2),
date VARCHAR(10),
edt_vcid VARCHAR(20),
File_ID VARCHAR(30),
organ VARCHAR(8),
species VARCHAR(8)
")
or die(mysql_error());
echo "Table 'sampleInfo' Created<br />";

mysql_query("CREATE TABLE binBase(
BB_ID INT,
PRIMARY KEY(bb_ID))")
or die(mysql_error());
echo "Table 'binbase' Created<br />";

mysql_query("CREATE TABLE experiment_info(
Exp_ID VARCHAR(15),
PRIMARY KEY(exp_ID))")
or die(mysql_error());
echo "Table 'experimentInfo' Created<br />";

//FOREIGN KEY(file_ID) REFERENCES sampleInfo(file_ID),
//FOREIGN KEY(bb_ID) REFERENCES binBase(bb_ID),
//FOREIGN KEY(exp_ID) REFERENCES experimentInfo(exp_ID),
mysql_query("CREATE TABLE msdata(
Exp_ID  VARCHAR(15),
BB_ID  INT,
File_ID VARCHAR(30),
Abundancy INT)")
or die(mysql_error());
echo "Table 'msdata' Created<br />";
mysql_query(mysql_close());
?>
