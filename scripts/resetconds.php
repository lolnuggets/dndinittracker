<?php

	include "db.php";

	$sql = "drop table conditions;";
	$con->query($sql);

	$sql = "create table conditions (
				id char(30),
				blinded bit,
				charmed bit,
				deafened bit,
				frightened bit,
				grappled bit,
				incapacitated bit,
				invisible bit,
				paralyzed bit,
				petrified bit,
				poisoned bit,
				prone bit,
				restrained bit,
				stunned bit,
				unconscious bit,
				exhaustion int
			)";
	$con->query($sql);