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


class ipcheck {

	public $error = null;

	public function get(){
		global $database, $config;
			$tpl = new Template('ipcheck.html');
			$hosts=$database->getHosts();
			foreach($hosts as $key => $item){
					$hostname= $item['name'];
					$nodelength=strlen($item['node']);
					$ipadress=substr($item['node'],0,$nodelength-3);
					$output = array();
					exec("nslookup $hostname", $output);
					$outipadress=substr($output[4],9,15);
					if($ipadress!=$outipadress){
						$tpl->setVar('name', $hostname);
						$tpl->setVar('node', $ipadress);
						$tpl->setVar('realaddress', $outipadress);
						$tpl->parse('entry');
					}
			}
			$content = $tpl->get();
			return array('title'=>'IPDB :: Groups',
						 'content'=>$content);
	}	
}


?>
