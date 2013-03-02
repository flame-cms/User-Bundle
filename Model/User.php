<?php
/**
 * User
 *
 * @author  Jiří Šifalda
 * @package Flame
 *
 * @date    09.07.12
 */

namespace Flame\CMS\UserBundle\Model;

/**
 * @Entity
 */
class User extends \Flame\Doctrine\Entity
{
    /**
     * @Column(type="string", length=128)
     */
    protected $password;

    /**
     * @Column(type="string", length=25)
     */
    protected $role;

    /**
     * @Column(type="string", length=100, unique=true)
     */
    protected $email;

	/**
	 * @Column(type="string", length=250)
	 */
	protected $name;

	/**
	 * @Column(type="string", length=500)
	 */
	protected $about;

	/**
	 * @Column(type="date")
	 */
	protected $birthday;

	/**
	 * @Column(type="string", length=150)
	 */
	protected $web;

	/**
	 * @Column(type="string", length=100)
	 */
	protected $facebook;

	/**
	 * @Column(type="string", length=100)
	 */
	protected $twitter;

    public function __construct($email, $password, $role)
    {
        $this->password = $password;
        $this->role = $role;
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($pass)
    {
        $this->password = (string) $pass;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = (string) $role;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

	public function setEmail($email)
	{
		$this->email = (string) $email;
		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = (string) $name;
		return $this;
	}

	public function getAbout()
	{
		return $this->about;
	}

	public function setAbout($about)
	{
		$this->about = (string) $about;
		return $this;
	}

	public function getBirthday()
	{
		return $this->birthday;
	}

	public function setBirthday(DateTime $birthday)
	{
		$this->birthday = $birthday;
		return $this;
	}
	public function getWeb()
	{
		return $this->web;
	}

	public function setWeb($web)
	{
		$this->web = (string) $web;
		return $this;
	}

	public function getFacebook()
	{
		return $this->facebook;
	}

	public function setFacebook($facebook)
	{
		$this->facebook = (string) $facebook;
		return $this;
	}

	public function getTwitter()
	{
		return $this->twitter;
	}

	public function setTwitter($twitter)
	{
		$this->twitter = (string) $twitter;
		return $this;
	}

	public function __toString()
	{
		return (string) $this->email;
	}
}
