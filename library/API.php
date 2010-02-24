<?php
	function antiXss($text){
		return strip_tags($text);	
	}

	function checkRememberMe(){
		if(isset($_COOKIE['tweet_id']) and isset($_COOKIE['oauth_token'])){
			
		}
	}
	

