<?php

	include "db.php";

	$sql = "drop table npcinfo;";
	$con->query($sql);

	$sql = "create table npcinfo (
				id char(32),
				level int,
				maxhp int,
				ac int,
				passper int,
				passivs int,
				passins int,
			    darkvision int,
			    hostility char(9),
			    hidden bit,
			    notes mediumtext,
    			statcard mediumtext
			)";
	$con->query($sql);