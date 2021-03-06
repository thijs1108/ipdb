<?php

/*
Copyright 2015 Topicus Onderwijs bv (http://www.topicus.nl)
Author: Thijs Beltman <t.beltman@hotmail.nl>

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

*/

/* Set logging to ./log/ipdb.log */
ini_set('error_log', 'log/ipdb.log');
ini_set('log_errors', 'on');
ini_set('display_errors','off');
error_reporting(E_ALL);


/* Include necessary files */
require_once 'include/actions.php';
require_once 'include/functions.php';
require_once 'include/config.php';
require_once 'include/database.php';
require_once 'include/menu.php';
require_once 'include/session.php';
require_once 'include/template.php';
require_once 'include/tree.php';


/* Set some settings */
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
$error = false;
$debugstr = '';


/* It's good to know where we are */
$root = dirname(__FILE__);


/* Version */
$version = 0.2;


/* Check for incoming RESTful request */
$rest = preg_match('/^(get|post|put|delete)\/(.*)/i', $_SERVER["QUERY_STRING"], $matches) ?
	array('type'=>strtolower($matches[1]), 'request'=>explode('/', $matches[2])) : false;
/* Alternative RESTful request; e.g. http://127.0.0.1/?get=/node/::0/0 */
if (!$rest) {
	$request = array_change_key_case($_REQUEST, CASE_LOWER);
	if (($method = array_intersect(array('get', 'post', 'put', 'delete'), array_keys($request))) &&
		preg_match('/^\//', $request[$method[0]]))
		$rest = array('type'=>$method[0], 'request'=>explode('/', $request[$method[0]]));
}


/* Read configuration file */
$config = new Config();
if ($config->error)
	fatal($config->error);


/* Start the session */
$session = new Session($config->session);
if ($session->error)
	fatal($session->error);


/* Initialize the database */
$database = new Database($config->database);
if ($database->error)
	fatal($database->error);


/* Handle RESTful API request */
if ($rest) {
	require_once 'include/rest.php';
	exit;
}


if (!$database->hasDatabase())
	request('page', 'initdb', true);
else if (!$session->start_websession())
	request('page', 'login', true);
else if ($database->hasUpgrade())
	request('page', 'upgradedb', true);
else if (request('dummy')=='dummy') {
	header('Content-type: Content-type: application/json; charset=utf-8');
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Fri, 15 Aug 2003 15:00:00 GMT'); /* Remember my wedding day */
	echo json_encode(array());
	exit;
}


/* Check if we need to act */
request('cancel', null, true);
if (($action = request('action')) &&
	(request('cancel')!='cancel'))
	acton($action);

/* Set default page to fetch */
$page = request('page', 'main');


/* Fetch the selected page */
if (!file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'include/pages'.DIRECTORY_SEPARATOR.$page.'.php'))
	fatal('No code defined for page '.$page);

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'include/pages'.DIRECTORY_SEPARATOR.$page.'.php';
$pageobj = new $page();
if (method_exists($pageobj, 'get')) {
	$pagedata = $pageobj->get();
	if ($pageobj->error)
		fatal($pageobj->error);
}


/* Send back the requested content */
send($pagedata);

?>
