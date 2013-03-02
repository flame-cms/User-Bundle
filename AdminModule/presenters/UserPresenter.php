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
     * @var \Flame\CMS\UserBundle\Model\UserFacade
     */
    private $userFacade;

	/**
	 * @var \Flame\CMS\UserBundle\Forms\UserEditFormFactory $userEditFormFactory
	 */
	private $userEditFormFactory;

	/**
	 * @var \Flame\CMS\UserBundle\Forms\UserAddFormFactory $userAddFormFactory
	 */
	private $userAddFormFactory;

	/**
	 * @var \Flame\CMS\UserBundle\Forms\UserPasswordFormFactory $userPasswordFormFactory
	 */
	private $userPasswordFormFactory;

	/**
	 * @param \Flame\CMS\UserBundle\Forms\UserPasswordFormFactory $userPasswordFormFactory
	 */
	public function injectUserPasswordFormFactory(\Flame\CMS\UserBundle\Forms\UserPasswordFormFactory $userPasswordFormFactory)
	{
		$this->userPasswordFormFactory = $userPasswordFormFactory;
	}

	/**
	 * @param \Flame\CMS\UserBundle\Forms\UserAddFormFactory $userAddFormFactory
	 */
	public function injectUserAddFormFactory(\Flame\CMS\UserBundle\Forms\UserAddFormFactory $userAddFormFactory)
	{
		$this->userAddFormFactory = $userAddFormFactory;
	}

	/**
	 * @param \Flame\CMS\UserBundle\Forms\UserEditFormFactory $userEditFormFactory
	 */
	public function injectUserEditFormFactory(\Flame\CMS\UserBundle\Forms\UserEditFormFactory $userEditFormFactory)
	{
		$this->userEditFormFactory = $userEditFormFactory;
	}

    /**
     * @param \Flame\CMS\UserBundle\Model\UserFacade $userFacade
     */
    public function injectUserFacade(\Flame\CMS\UserBundle\Model\UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

	public function renderDefault()
	{
		$this->template->users = $this->userFacade->getLastUsers();
	}

    /**
     * @param $id
     */
    public function actionEdit($id = null)
	{
		if($id === null) $id = $this->getUser()->getId();

		if(!$this->getUser()->isAllowed('Admin:User', 'editAnother')){
			if(!$this->user = $this->userFacade->getOne($id)){
				$this->flashMessage('User does not exist or you dont have permission');
				$this->redirect('Dashboard:');
			}
		}

		$this->user = $this->userFacade->getOne($id);

	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentUserEditForm()
	{
		$form = $this->userEditFormFactory->configure($this->user)->createForm();
		$form->onSuccess[] = $this->lazyLink('this');
		return $form;
	}


	/**
	 * @return Forms\Users\UserAddForm|\Nette\Application\UI\Form
	 */
	protected function createComponentUserAddForm()
	{
		$form = $this->userAddFormFactory->createForm();
		$form->onSuccess[] = $this->lazyLink('default');
		return $form;
	}

    /**
     * @return ChangePasswordForm
     */
    protected function createComponentUserPasswordForm()
	{
		$form = $this->userPasswordFormFactory->configure($this->getUser())->createForm();
		$form->onSuccess[] = $this->lazyLink('Dashboard:');
        return $form;
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
			$user = $this->userFacade->getOne((int) $id);

			if(!$user){
				$this->flashMessage('User with required ID does not exist');
			}else{
				$this->userFacade->delete($user);
			}
		}

		if(!$this->isAjax()){
			$this->redirect('this');
		}else{
			$this->invalidateControl('users');
		}
	}
}
