<?php
	define("CONSUMER_KEY", "M1TVhDl33zZ47cfpc5Sug");
	define("CONSUMER_SECRET", "Wlr2EejIid946jfnoxNDuzHn89aDfWYvDaVBGzTIEE");
	define("OAUTH_CALLBACK", "http://localhost/BareHuesosOAuth/oauth/callback");		

	Zend_Loader::loadClass('User');			
	$this->view->baseUrl = $this->_request->getBaseUrl();
	if($_SESSION['access_token']['oauth_token'] && $_SESSION['access_token']['oauth_token_secret']){
		$user = new User();
		$this->view->user = $user->fetchRow($user->select()->where('oauth_token = ? ', $_SESSION['access_token']['oauth_token'])->where('oauth_token_secret = ? ', $_SESSION['access_token']['oauth_token_secret']));
		if($this->view->user){
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $this->view->user->oauth_token, $this->view->user->oauth_token_secret);
			$this->view->content = $connection->get('account/verify_credentials');
			if(!$this->view->content->error){
				$this->conectionStatus = 'Success';
			}else{
				$this->conectionStatus = 'Twitter Session timed out';
			}
		}else{
			$this->conectionStatus = 'Invalid Session/Database Relation';
		}
	}else{
		$this->conectionStatus = 'BareHuesos Session timed out or no seccion at all';
	}
		
		

?>
