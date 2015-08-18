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


class groups {

	public $error = null;

	public function get(){
		global $database, $config;
		if (isset($_GET['group'])){
			$even = False;
			$tpl = new Template('groupsset.html');
			$children=$database->getGroupsSet($_GET['group']);
			foreach($children as $key => $item){
					$tpl->setVar('name', $item['name']);
					$tpl->setVar('node', $item['node']);
					$tpl->setVar('description', $item['description']);
					$tpl->setVar('responsible', $item['responsible']);
					$tpl->setVar('remarks', $item['remarks']);
					$tpl->setVar('oddeven', ' class="'.($even ? 'even' : 'odd').
						  (isset($child['unused']) ? ' unused' : '').'"');
					$even=!$even;
					$tpl->parse('entry');
					
			}
			$tpl->setVar('group', $_GET['group']);
		}
		else{
			$even=False;
			$tpl = new Template('groups.html');
			$servergroupold="";
			$children=$database->getGroups();
			foreach($children as $key => $item){
				$servergroup=$item['servergroup'];
				if($servergroupold!=$servergroup){
					$tpl->setVar('group', $item['servergroup']);
					$tpl->setVar('oddeven', ' class="'.($even ? 'even' : 'odd').
						  (isset($child['unused']) ? ' unused' : '').'"');
					$even=!$even;
					$tpl->parse('entry');
					
				}
				$servergroupold=$servergroup;
			}
		}
		$content = $tpl->get();
			return array('title'=>'IPDB :: Groups',
						 'content'=>$content);
	}	
}


?>
