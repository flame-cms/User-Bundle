<?php
/**
 * UserManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\UserBundle\Model;

class UserManager extends \Flame\Model\Manager
{

	/** @var UserFacade */
	private $userFacade;

	/** @var \Flame\CMS\UserBundle\Security\Authenticator */
	private $authenticator;

	/**
	 * @param \Flame\CMS\UserBundle\Security\Authenticator $authenticator
	 */
	public function injectAuthenticator(\Flame\CMS\UserBundle\Security\Authenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}

	/**
	 * @param UserFacade $userFacade
	 */
	public function injectUserFacade(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	/**
	 * @param $data
	 * @return User
	 * @throws \Nette\InvalidArgumentException
	 */
	public function create($data)
	{
		$values = $this->validateInput($data, array('email', 'password', 'role'));

		if($this->userFacade->getByEmail($values['email'])){
			throw new \Nette\InvalidArgumentException('Email ' . $values['email'] . ' exist.');
		}else{
			$user = new \Flame\CMS\UserBundle\Model\User(
				$values['email'],
				$this->authenticator->calculateHash($values['password']),
				$values['role']
			);


			$this->userFacade->save($user);
			return $user;
		}

	}

	/**
	 * @param $data
	 * @return mixed
	 * @throws \Nette\InvalidArgumentException
	 */
	public function edit($data)
	{
		$values = $this->validateInput($data, array('about', 'birthday', 'web', 'facebook', 'twitter', 'id', 'name'));

		if($user = $this->userFacade->getOne($values->id)){
			$user->setAbout($values['about'])
				->setBirthday($values['birthday'])
				->setWeb($values['web'])
				->setFacebook($values['facebook'])
				->setTwitter($values['twitter'])
				->setName($values->name);

			$this->userFacade->save($user);
			return $user;
		}else{
			throw new \Nette\InvalidArgumentException('User with ID "' . $values->id . '" does not exist');
		}
	}

	/**
	 * @param $data
	 * @return mixed
	 * @throws \Nette\InvalidArgumentException
	 */
	public function changePassword($data)
	{
		$values = $this->validateInput($data, array('id', 'newPassword'));

		if($user = $this->userFacade->getOne($values->id)){
			$user->setPassword($this->authenticator->calculateHash($values->newPassword));
			$this->userFacade->save($user);
			return $user;
		}else{
			throw new \Nette\InvalidArgumentException('User with ID "' . $values->id . '" does not exist');
		}
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws \Nette\InvalidArgumentException
	 */
	public function delete($id)
	{
		if($user = $this->userFacade->getOne($id)){
			$this->userFacade->delete($user);
			return true;
		}else{
			throw new \Nette\InvalidArgumentException('User with ID "' . $id . '" does not exist');
		}
	}
}
