<?php

	include "db.php";

	$sql = "drop table tempinfo;";
	$con->query($sql);

	$sql = "create table tempinfo (
				id char(32),
				hp int,
				initiative int,
			    initprio int,
			    currentturn int,
			    player bit
			)";
	$con->query($sql);

	