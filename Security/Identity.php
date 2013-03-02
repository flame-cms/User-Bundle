<?php
/**
 * Identity.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    22.07.12
 */

namespace Flame\CMS\UserBundle\Security;

class Identity extends \Nette\Security\Identity
{

	/**
	 * @param \Flame\CMS\UserBundle\Model\User $user
	 */
	public function __construct(\Flame\CMS\UserBundle\Model\User $user)
	{
		$data = $user->toArray();

		unset($data['password']);

		parent::__construct($user->getId(),$user->getRole(), $data);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) (isset($this->email)) ? $this->email : '';
	}

}
