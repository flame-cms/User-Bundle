<?php
/**
 * UserAddForm.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\CMS
 *
 * @date    14.10.12
 */

namespace Flame\CMS\UserBundle\Forms;

class UserAddForm extends \Flame\CMS\UserBundle\Application\UI\Form
{

	/** @var \Flame\CMS\UserBundle\Model\UserManager */
	private $userManager;

	/**
	 * @param \Flame\CMS\UserBundle\Model\UserManager $userManager
	 */
	public function injectUserManager(\Flame\CMS\UserBundle\Model\UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	public function __construct()
	{
		parent::__construct();
		$this->configure();
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param UserAddForm $form
	 */
	public function formSubmitted(UserAddForm $form)
	{
		try {
			$this->userManager->create($form->getValues());
			$form->presenter->flashMessage('User was created', 'success');
		}catch (\Nette\InvalidArgumentException $ex){
			$form->addError($ex->getMessage());
		}
	}

	private function configure()
	{

		$this->addSelect('role', 'Role:')
			->setItems(array('user', 'moderator', 'administrator'), false)
			->setRequired();

		$this->addText('email', 'EMAIL:', 60)
			->addRule(self::MAX_LENGTH, null, 100)
			->addRule(self::EMAIL)
			->setRequired();

		$this->addPassword('password', 'Password:', 60)
			->setRequired();

		$this->addPassword('passwordTwo', 'Password (check):', 60)
			->addRule(self::EQUAL, 'Entered passwords is not equal. Try it again.', $this['password']);

		$this->addSubmit('send', 'Add')
			->setAttribute('class', 'btn-primary');
	}

}
