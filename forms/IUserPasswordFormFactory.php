<?php
/**
 * IUserPasswordFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\UserBundle\Forms;

interface IUserPasswordFormFactory
{

	/**
	 * @return UserPasswordForm
	 */
	public function create();

}
