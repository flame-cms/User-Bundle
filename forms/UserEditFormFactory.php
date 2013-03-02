<?php
/**
 * UserEditFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\CMS
 *
 * @date    14.10.12
 */

namespace Flame\CMS\UserBundle\Forms;

class UserEditFormFactory extends \Nette\Object
{

	/**
	 * @var UserEditForm
	 */
	protected $form;

	/**
	 * @var \Flame\CMS\UserBundle\Model\User
	 */
	private $user;

	/**
	 * @var \Flame\CMS\UserBundle\Model\UserFacade $userFacade
	 */
	private $userFacade;

	/**
	 * @var \Flame\CMS\UserBundle\ModelInfo\UserInfoFacade $userInfoFacade
	 */
	private $userInfoFacade;

	/**
	 * @param \Flame\CMS\UserBundle\ModelInfo\UserInfoFacade $userInfoFacade
	 */
	public function injectUserInfoFacade(\Flame\CMS\UserBundle\ModelInfo\UserInfoFacade $userInfoFacade)
	{
		$this->userInfoFacade = $userInfoFacade;
	}

	/**
	 * @param \Flame\CMS\UserBundle\Model\UserFacade $userFacade
	 */
	public function injectUserFacade(\Flame\CMS\UserBundle\Model\UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	/**
	 * @param $user
	 * @return UserEditFormFactory
	 */
	public function configure($user)
	{

		$this->user = $user;

		$this->form = new UserEditForm();

		$userInfo = $this->user->getInfo();
		$defaults = array_merge(
			array('email' => $this->user->getEmail()),
			$userInfo ? $userInfo->toArray() : array()
		);
		$this->form->configure($defaults);
		$this->form->onSuccess[] = $this->formSubmitted;

		return $this;
	}

	/**
	 * @param UserEditForm $form
	 */
	public function formSubmitted(UserEditForm $form)
	{
		$values = $form->getValues();

		if($info = $this->user->getInfo()){
			$info->setName($values['name'])
				->setAbout($values['about'])
				->setBirthday($values['birthday'])
				->setWeb($values['web'])
				->setFacebook($values['facebook'])
				->setTwitter($values['twitter']);
			$this->userInfoFacade->save($info);
		}else{
			$info = new \Flame\CMS\UserBundle\ModelInfo\UserInfo($values['name']);
			$info->setAbout($values['about'])
				->setBirthday($values['birthday'])
				->setWeb($values['web'])
				->setFacebook($values['facebook'])
				->setTwitter($values['twitter']);
			$this->userInfoFacade->save($info);
			$this->user->setInfo($info);
			$this->userFacade->save($this->user);
		}

		$form->presenter->flashMessage('User was edited', 'success');
	}

}
