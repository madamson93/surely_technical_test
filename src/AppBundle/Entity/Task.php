<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 15/03/2018
 * Time: 21:50
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=256)
	 */
	private $task_details;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getTaskDetails()
	{
		return $this->task_details;
	}

	/**
	 * @param mixed $task_details
	 */
	public function setTaskDetails($task_details)
	{
		$this->task_details = $task_details;
	}


}