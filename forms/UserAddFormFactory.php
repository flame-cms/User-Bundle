<?php
/**
 * UserAddFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\CMS
 *
 * @date    14.10.12
 */

namespace Flame\CMS\UserBundle\Forms;

class UserAddFormFactory extends \Nette\Object
{

	/**
	 * @var \Flame\CMS\UserBundle\Model\UserFacade $userFacade
	 */
	private $userFacade;

	/**
	 * @var \Flame\CMS\Security\Authenticator $authenticator
	 */
	private $authenticator;

	/**
	 * @param \Flame\CMS\Security\Authenticator $authenticator
	 */
	public function injectAuthenticator(\Flame\CMS\Security\Authenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}

	/**
	 * @param \Flame\CMS\UserBundle\Model\UserFacade $userFacade
	 */
	public function injectUserFacade(\Flame\CMS\UserBundle\Model\UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	/**
	 * @return UserAddForm|\Nette\Application\UI\Form
	 */
	public function createForm()
	{
		$form = new UserAddForm();
		$form->configure();
		$form->onSuccess[] = $this->formSubmitted;
		return $form;
	}

	/**
	 * @param UserAddForm $form
	 */
	public function formSubmitted(UserAddForm $form)
	{
		$values = $form->getValues();

		if($this->userFacade->getByEmail($values['email'])){
			$form->presenter->flashMessage('Email ' . $values['email'] . ' exist.');
		}else{
			$user = new \Flame\CMS\UserBundle\Model\User(
				$values['email'],
				$this->authenticator->calculateHash($values['password']),
				$values['role']
			);

			$this->userFacade->save($user);
			$form->presenter->flashMessage('User was added', 'success');
		}
	}

}
