<?php

/*
Copyright 2011 Previder bv (http://www.previder.nl)
Author: Robin Elfrink <robin@15augustus.nl>

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


class groups {

	public $error = null;

	public function get(){
		global $database, $config;
		$tpl = new Template('groups.html');
		$databaseservername="localhost";
		$databaseusername="root";
		$databasepassword="changeme";
		$databasename="ipdb";
		$databaseconnection= mysqli_connect($databaseservername, $databaseusername, $databasepassword, $databasename);
		$oldservergroup="";
		$output="";

		if (!$databaseconnection){
			die("Connection failed: " . mysqli_connect_error);
		}
		$sql="SELECT servergroup ";
		$sql.="FROM ipdb_ip WHERE `bits` = 128 ORDER BY servergroup";
		$result= mysqli_query($databaseconnection,$sql);

		if (mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)){
				if($row['servergroup']!=$oldservergroup){
					$tpl->setVar('group', $row['servergroup']);
					$tpl->parse('entry');
				}
				$oldservergroup = $row['servergroup'];
			}
		}
		mysqli_close($databaseconnection);
		
		$content = $tpl->get();
		return array('title'=>'IPDB :: Groups',
					 'content'=>$content);
	}
}


?>
