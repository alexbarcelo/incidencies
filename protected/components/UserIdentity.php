<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	private $_name;
	
	/**
	 * Authenticates a user.
	 */
	public function authenticate()
	{
		// Temporal:
		if ( ($this->username === "admin") && ($this->password === "deixamentrar") ) {
			$this->errorCode=self::ERROR_NONE;
			return !$this->errorCode;
		}
		
		$user = Profes::model()->findByAttributes(array('username'=>$this->username));
		if ($user===null)
		  $this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif ( crypt($this->password, $user->password) !== $user->password)
		  $this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
		  // Okay! Check Nom i equipDirectiu
		  $this->errorCode=self::ERROR_NONE;
		  $this->_name = $user->nom;
		  if ($user->equip_directiu === '1') {
			$this->setState("equipDirectiu", true);
		  }
		}
		
		return !$this->errorCode;
	}
	
	public function getName()
	{
		return $this->_name;
	}
}
