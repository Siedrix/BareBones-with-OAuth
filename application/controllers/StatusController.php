<?php
// @author: Santiago Zavala
// @date: 6/11/2008
class StatusController extends Zend_Controller_Action
{
	
	function init()
	{
		include('./application/init.php');
		include('./application/oauthconfig.php');
	}

	// @author: Santiago Zavala
	// @date: 6/11/2008
	function indexAction(){
	
	
	}
	
	function updateAction()
	{		
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $this->view->user->oauth_token, $this->view->user->oauth_token_secret);
		$connection->post('statuses/update', array('status' => $_POST['status']));
		$this->view->message = "Success";
	}
}