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


class login {


	public $error = null;


	public function get() {
		global $session, $config, $version;
		$tpl = new Template('login.html');
		$tpl->setVar('username', request('username', isset($_SESSION['username']) ? $_SESSION['username'] : ''));
		return array('title'=>'IPDB :: Login',
					 'content'=>$tpl->get(),
					 'notify'=>($session->error ? array('type'=>'error', 'message'=>$session->error) : false),
					 'commands'=>array("$('#username').select()",
									   "$('#tree').remove()",
									   "$('nav ul').remove()"));
	}


}


?>
