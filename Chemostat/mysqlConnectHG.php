<?php
mysql_connect("localhost", "nturner", "LOB4steR") or die(mysql_error());
////Drop Database ; localhost: root
//mysql_query("DROP DATABASE my_db");
////Create Database
//mysql_query("CREATE DATABASE viral_dark_matter") or die(mysql_error());
//echo "Database Created<br />";
////Select Database
mysql_select_db("viral_dark_matter") or die(mysql_error());
//Create Tables

mysql_query("CREATE TABLE sample_info(
Samp_ID INT(10) NOT NULL AUTO_INCREMENT,
Reactor_ID VARCHAR(2),
Sample_ID INT(2),
bact_external_id VARCHAR(10),
vc_id INT(5),
Rep INT(2),
Genotype VARCHAR(20),
Vector VARCHAR(30),
Specie VARCHAR(30),
Ms_date DATE,
File_ID VARCHAR(30) NOT NULL UNIQUE,
Comment VARCHAR(50),
PRIMARY KEY(samp_ID)
)")
or die(mysql_error());
echo "Table 'sampleInfo' Created<br />";

mysql_query("CREATE TABLE binBase(
Bin_ID INT(10) NOT NULL AUTO_INCREMENT,
BB_ID INT(10) NOT NULL UNIQUE,
BB_Name VARCHAR(50),
Kegg_ID VARCHAR(10),
PubChem_ID INT(10),
Ret_Index INT(10),
Quant_mz INT(5),
Mass_spec TEXT(6000),
PRIMARY KEY(bin_ID))")
or die(mysql_error());
echo "Table 'binbase' Created<br />";

mysql_query("CREATE TABLE experiment_info(
Exp_ID INT(4) NOT NULL AUTO_INCREMENT,
Exp_name VARCHAR(30) NOT NULL UNIQUE,
Comment VARCHAR(50),
PRIMARY KEY(Exp_ID))")

or die(mysql_error());
echo "Table 'experimentInfo' Created<br />";

mysql_query("CREATE TABLE chemo_data(
Chemo_ID INT(10) NOT NULL AUTO_INCREMENT,
Samp_ID INT(10) REFERENCES sample_info(Samp_ID),
Bin_ID  INT(10) REFERENCES binBase(Bin_ID),
Exp_ID INT(4) REFERENCES experiment_info(Exp_ID),
Abundance INT(10),
PRIMARY KEY(chemo_ID))")
or die(mysql_error());
echo "Table 'msdata' Created<br />";

?>