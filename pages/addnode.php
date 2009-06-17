<?php

/*
Copyright 2009 Introweb Nederland bv
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

$Id$
*/


class addnode {


	public $error = null;


	public function get() {
		global $config, $database;
		$skin = new skin($config->skin);
		$skin->setFile('node.html');
		if ($node = request('node')) {
			$data = $database->getAddress($node);
			if (count($config->columns)>0)
				foreach ($config->columns as $column=>$details) {
					$skin->setVar('name', $column);
					$skin->setVar('fullname', $details['name']);
					$skin->setVar('value', $database->getColumn($column, $node));
					$skin->parse('column', true);
				}
			$skin->setVar('address', ip2address($data['address']));
			$skin->setVar('bits', (strcmp($data['address'], '00000000000000000000000100000000')<0 ? $data['bits']-96 : $data['bits']));
			$skin->setVar('description', $data['description']);
		}
		$skin->hideBlock('changenode');
		$skin->parse('addnode');
		$content = $skin->get();
		return array('title'=>$title,
					 'content'=>$content);
	}


}


?>