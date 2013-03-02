<?php
/**
 * UserFacade
 *
 * @author  Jiří Šifalda
 * @package
 *
 * @date    09.07.12
 */

namespace Flame\CMS\UserBundle\Model;

class UserFacade extends \Flame\Doctrine\Model\Facade
{

	/**
	 * @var string
	 */
	protected $repositoryName = '\Flame\CMS\UserBundle\Model\User';

	/**
	 * @return array
	 */
	public function getLastUsers()
    {
        return $this->repository->findAll();
    }

	/**
	 * @param $email
	 * @return object
	 */
	public function getByEmail($email)
    {
        return $this->repository->findOneBy(array('email' => $email));
    }
}
