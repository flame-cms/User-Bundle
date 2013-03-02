<?php
/**
 * IInFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\UserBundle\Forms\Sign;

interface IInFormFactory
{

	/**
	 * @return InForm
	 */
	public function create();

}
