<?php

/*  Copyright 2008  Robin Elfrink  (email : robin@15augustus.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

$Id$
*/


/* Include necessary files */
require_once 'functions.php';
require_once 'classes/config.php';
require_once 'classes/database.php';
require_once 'classes/session.php';
require_once 'classes/skin.php';


/* Set default page to fetch */
$page = 'main';


/* Read configuration file */
$config = new Config();
if ($config->error)
	exit('Error: '.$config->error);


/* Initialize the database */
$database = new Database($config->database);
if ($database->error)
	exit('Error: '.$database->error);
if (!$database->hasDatabase())
	$page = 'initdb';
else if ($database->hasUpgrade())
	$page = 'upgradedb';


/* Initialize the skin */
$skin = new Skin($config->skin);
if ($skin->error)
	exit('Error: '.$skin->error);



/* Close the database */
$database->close();


?>
