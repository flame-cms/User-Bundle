<?php

namespace Flame\CMS\UserBundle\Security;

use Nette\Security as NS;


/**
 * Users authenticator.
 */
class Authenticator extends \Flame\Security\Authenticator
{

	/**
	 * @var \Flame\CMS\UserBundle\Model\UserFacade
	 */
	private $userFacade;

	/**
	 * @param \Flame\CMS\UserBundle\Model\UserFacade $usersFacade
	 */
	public function __construct(\Flame\CMS\UserBundle\Model\UserFacade $usersFacade)
	{
		$this->userFacade = $usersFacade;
	}

	/**
	 * @param array $credentials
	 * @return Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
	    $user = $this->userFacade->getByEmail($email);

	    if (!$user) {
	        throw new NS\AuthenticationException("Email '$email' not found.", self::IDENTITY_NOT_FOUND);
	    }

	    if ($user->password !== $this->calculateHash($password, $user->password)) {
	        throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
	    }

	    return new Identity($user);
	}

}
