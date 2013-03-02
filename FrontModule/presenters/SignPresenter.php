<?php

namespace Flame\CMS\FrontModule;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends FrontPresenter
{

	/**
	 * @var string|null
	 */
	private $backlink;

	/**
	 * @autowire
	 * @var \Flame\CMS\UserBundle\Forms\Sign\IInFormFactory
	 */
	protected $inFormFactory;

	public function startup()
	{
		parent::startup();

		if($this->getUser()->isLoggedIn()){
			$this->redirect(':Admin:Dashboard:');
		}
	}

	/**
	 * @param null $backlink
	 */
	public function actionIn($backlink = null)
	{
		$this->backlink = $backlink;
	}

	/**
	 * @param \Flame\CMS\UserBundle\Forms\Sign\InForm $form
	 */
	public function formSubmitted(\Flame\CMS\UserBundle\Forms\Sign\InForm $form)
	{
		$values = $form->getValues();

		try {

			if ($values->remember) {
				$this->getUser()->setExpiration('+ 14 days', false);
			} else {
				$this->getUser()->setExpiration('+ 2 hours', true);
			}

			$this->getUser()->login($values->email, $values->password);

			if($this->backlink)
				$this->restoreRequest($this->backlink);

		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

	/**
	 * @return mixed
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->inFormFactory->create();
		$form->onSuccess[] = $this->formSubmitted;
		$form->onSuccess[] = $this->lazyLink(':Admin:DashBoard:');
		return $form;
	}


}
