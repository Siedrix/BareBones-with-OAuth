<?php
class LogoutController extends Zend_Controller_Action {
	function init(){
		include('./application/init.php');
	}

	function indexAction(){
		session_destroy();
		$this->_redirect('/');
	}
}