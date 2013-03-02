<?php
/**
 * UserPasswordForm.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\CMS
 *
 * @date    14.10.12
 */

namespace Flame\CMS\UserBundle\Forms;

class UserPasswordForm extends \Flame\CMS\UserBundle\Application\UI\Form
{

	/** @var \Flame\CMS\UserBundle\Model\UserManager */
	private $userManager;

	/** @var \Flame\CMS\UserBundle\Security\User */
	private $user;

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
	 * @param \Flame\CMS\UserBundle\Security\User $user
	 */
	public function injectUser(\Flame\CMS\UserBundle\Security\User $user)
	{
		$this->user = $user;
		$this->setId($user->getId());
	}

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
	 * @param UserPasswordForm $form
	 */
	public function formSubmitted(UserPasswordForm $form)
	{

		$values = $form->getValues();

		try {

			$this->authenticator->authenticate(array($this->user->getModel()->getEmail(), $values->oldPassword));
			$this->userManager->changePassword($values);
			$form->presenter->flashMessage('Password was changed.', 'success');

		} catch (\Exception $ex) {
			$form->addError($ex->getMessage());
		}
	}

	private function configure()
	{
		$this->addPassword('oldPassword', 'Current password:', 30)
			->addRule(self::FILLED);
		$this->addPassword('newPassword', 'New password:', 30)
			->addRule(self::MIN_LENGTH, null, 6);
		$this->addPassword('confirmPassword', 'New password (verify):', 30)
			->addRule(self::FILLED)
			->addRule(self::EQUAL, 'Entered passwords is not equal. Try it again.', $this['newPassword']);
		$this->addSubmit('send', 'Change password')
			->setAttribute('class', 'btn-primary');
	}

}
