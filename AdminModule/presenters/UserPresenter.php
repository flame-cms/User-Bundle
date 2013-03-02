<?php

namespace Flame\CMS\AdminModule;

use Flame\CMS\UserBundle\Model\User;

class UserPresenter extends AdminPresenter
{

    /**
     * @var \Flame\CMS\UserBundle\Model\User
     */
    private $user;

	/**
	 * @autowire
	 * @var \Flame\CMS\UserBundle\Model\UserFacade
	 */
	protected $userFacade;

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
	 * @autowire
	 * @var \Flame\CMS\UserBundle\Forms\IUserAddFormFactory
	 */
	protected $userAddFormFactory;

	/**
	 * @autowire
	 * @var \Flame\CMS\UserBundle\Forms\IUserEditFormFactory
	 */
	protected $userEditFormFactory;

	/**
	 * @autowire
	 * @var \Flame\CMS\UserBundle\Forms\IUserPasswordFormFactory
	 */
	protected $userPasswordFormFactory;

	public function renderDefault()
	{
		$this->template->users = $this->userFacade->getLastUsers();
	}

    /**
     * @param $id
     */
    public function actionEdit($id = null)
	{
		if($id === null)
			$id = $this->getUser()->getId();

		if(!$this->getUser()->isAllowed('Admin:User', 'editAnother')){
			if(!$this->user = $this->userFacade->getOne($id)){
				$this->flashMessage('User does not exist or you dont have permission');
				$this->redirect('Dashboard:');
			}
		}

		$this->user = $this->userFacade->getOne($id);
		$this->template->user = $this->user;

	}


	/**
	 * @param $id
	 */
	public function handleDelete($id)
	{
		if($this->getUser()->getId() == $id){
			$this->flashMessage('You cannot delete yourself');
		}elseif(!$this->getUser()->isAllowed('Admin:User', 'delete')){
			$this->flashMessage('Access denied');
		}else{
			try {
				$this->userManager->delete($id);
			}catch (\Nette\InvalidArgumentException $ex){
				$this->flashMessage($ex->getMessage(), 'error');
			}
		}

		$this->redirect('this');
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentUserEditForm()
	{
		$default = array();
		if($this->user instanceof User)
			$default = $this->user->toArray();

		$form = $this->userEditFormFactory->create($default);
		$form->onSuccess[] = $this->lazyLink('this');
		return $form;
	}


	/**
	 * @return \Flame\CMS\UserBundle\Forms\UserAddForm
	 */
	protected function createComponentUserAddForm()
	{
		$form = $this->userAddFormFactory->create();
		$form->onSuccess[] = $this->lazyLink('default');
		return $form;
	}

    /**
     * @return ChangePasswordForm
     */
    protected function createComponentUserPasswordForm()
	{
		$form = $this->userPasswordFormFactory->create();
		$form->onSuccess[] = $this->lazyLink('this');
        return $form;
	}
}
