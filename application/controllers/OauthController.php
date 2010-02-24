<?php
// @author: Santiago Zavala
// @date: 6/11/2008
class OauthController extends Zend_Controller_Action
{
	
	function init()
	{
		include('./application/init.php');
	}

	// @author: Santiago Zavala
	// @date: 6/11/2008
	function indexAction()
	{
		session_start();
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			header('Location: ./clearsessions');
		}
		$access_token = $_SESSION['access_token'];
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		$this->view->content = $connection->get('account/verify_credentials');		
		
		$user = new User();
		
		$data = array(
			'oauth_token' => $access_token['oauth_token'],
			'oauth_token_secret' => $access_token['oauth_token_secret'],
			'name' => $this->view->content->name,
			'screen_name' => $this->view->content->screen_name,
			'tweet_id' => $this->view->content->id
		);
		$user->insertOrUpdate($data);
	}
	
	function callbackAction()
	{
		session_start();

		if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
		  $_SESSION['oauth_status'] = 'oldtoken';
		  header('Location: ./clearsessions');
		}

		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

		$_SESSION['access_token'] = $access_token;

		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);

		if (200 == $connection->http_code) {
			$_SESSION['status'] = 'verified';
			header('Location: ./index');
			$this->_redirect('/');
		} else {
			$this->_redirect('/oauth/clearsessions');
		}
		
	}
	
	function testAction()
	{
		
		
	}
	
	function clearsessionsAction()
	{
		/* Load and clear sessions */
		session_start();
		session_destroy();	
		$this->_redirect('/oauth/connect');
	}
	
	function connectAction()
	{
		
		
	}
	
	function redirectAction()
	{
		session_start();

		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		 
		$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		 
		switch ($connection->http_code) {
		  case 200:
			$url = $connection->getAuthorizeURL($token);
			header('Location: ' . $url); 
			break;
			default:
			echo 'Could not connect to Twitter. Refresh the page or try again later.';
			break;
		}
		
	}	

	function logOutAction()
	{
		session_destroy();
		$this->_redirect('/');
	}	
}