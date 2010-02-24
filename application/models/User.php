<?php
class User extends Zend_Db_Table{
protected $_name = 'user';

	function insertOrUpdate($data){
		$currentUser = $this->fetchRow($this->select()->where('screen_name = ?', $data['screen_name']));
		if($currentUser){
			if($currentUser->name == $data['name'] && $currentUser->oauth_token == $data['oauth_token'] && $currentUser->oauth_token_secret == $data['oauth_token_secret']){
				echo 'User Hasnt change'; 
			}else{
				echo 'User Has change'; 
				$newData = array(
					'oauth_token' => $access_token['oauth_token'],
					'oauth_token_secret' => $access_token['oauth_token_secret'],
					'name' => $this->view->content->name
				);
				$where = $this->getAdapter()->quoteInto('id = ?', $currentUser->id);
				$this->update($data, $where);
			}
		}else{
			$this->insert($data);
		}
		
	}
}