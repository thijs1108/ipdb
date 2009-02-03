<?php

/*  Copyright 2009  Robin Elfrink  (email : robin@15augustus.nl)

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


class login {


	public $error = null;


	public function get() {
		global $session, $config;
		$skin = new Skin($config->skin);
		$skin->setFile('login.html');
		if ($session->error) {
			$skin->setBlock('errorheader');
			$skin->setVar('error', $session->error);
			$skin->parse('errorheader');
		} else
			$skin->hideBlock('errorheader');
		$skin->setVar('username', request('username', $_SESSION['username']));

		$content = $skin->get();
		$commands = "
if (getElement('username')) {
	getElement('username').focus();
	getElement('username').select();
}";
		return array('title'=>'IPDB :: Login',
					 'tree'=>'&nbsp;',
					 'commands'=>$commands,
					 'content'=>$content);
	}


}


?>
