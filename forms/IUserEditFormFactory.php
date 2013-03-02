<?php
/**
 * IUserEditFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\UserBundle\Forms;

interface IUserEditFormFactory
{

	/**
	 * @param array $default
	 * @return UserEditForm
	 */
	public function create(array $default = array());
}
