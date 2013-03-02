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

	/** @var array */
	private $data;

	/**
	 * @param \Flame\CMS\UserBundle\Model\User $user
	 */
	public function __construct(\Flame\CMS\UserBundle\Model\User $user)
	{
		$this->data = $user->toArray();

		unset($this->data['password']);

		parent::__construct($user->getId(),$user->getRole(), null);
	}

	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) (isset($this->data['email'])) ? $this->data['email'] : '';
	}

}
