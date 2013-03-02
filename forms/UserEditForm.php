<?php
/**
 * UserEditForm.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\CMS
 *
 * @date    14.10.12
 */

namespace Flame\CMS\UserBundle\Forms;

class UserEditForm extends \Flame\CMS\UserBundle\Application\UI\Form
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

	/**
	 * @param array $default
	 */
	public function __construct(array $default = array())
	{
		parent::__construct();
		$this->configure();
		$this->setDefaults($default);
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param UserEditForm $form
	 */
	public function formSubmitted(UserEditForm $form)
	{
		try {
			$this->userManager->edit($form->getValues());
			$form->presenter->flashMessage('User was edited.', 'success');
		}catch (\Nette\InvalidArgumentException $ex){
			$form->addError($ex->getMessage());
		}
	}

	private function configure()
	{
		$this->addText('name', 'Name:', 60)
			->addRule(self::MAX_LENGTH, null, 150);

		$this->addTextArea('about', 'About:', 60, 5)
			->addRule(self::MAX_LENGTH, null, 250);

		$this->addDatePicker('birthday', 'Birthday:')
			->setDefaultValue(new \DateTime())
			->addRule(self::VALID, 'Entered date is not valid');

		$this->addText('web', 'Web:', 60)
			->addRule(self::MAX_LENGTH, null, 150);

		$this->addText('facebook', 'Facebook:', 60)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addText('twitter', 'Twitter:', 60)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addText('email', 'EMAIL:', 60)
			->addRule(self::MAX_LENGTH, null, 100)
			->addRule(self::EMAIL)
			->controlPrototype->readonly = 'readonly';

		$this->addSubmit('send', 'Edit')
			->setAttribute('class', 'btn-primary');
	}

}
