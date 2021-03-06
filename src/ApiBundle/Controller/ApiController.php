<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 15/03/2018
 * Time: 22:10
 */

namespace ApiBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Task;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ApiController extends Controller
{

	private $taskEntity;
	const HTTP_METHOD_ERROR = 'The HTTP Method used is not allowed for this route. Please try again';
	const REQUEST_EMPTY_ERROR = 'The request body must contain the correct data. Please try again';

	/**
	 * ApiController constructor.
	 */
	public function __construct()
	{
		$this->taskEntity = new Task();
	}

	/**
	 * Route to create a new task
	 *
	 * @Route("api/tasks")
	 * @Method("POST")
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function newTasksAction(Request $request): JsonResponse
	{
		try {
			//decode request data, received as an object
			$requestData = json_decode($request->getContent());
			$entityManager = $this->getDoctrine()->getManager();

			//use new instance of task entity and setter method
			$this->taskEntity->setTaskDetails($requestData->task_details);

			// tells Doctrine you want to (eventually) save the Product (no queries yet)
			$entityManager->persist($this->taskEntity);

			// actually executes the queries (i.e. the INSERT query)
			$entityManager->flush();

			$response = [
				'message' => "New item saved with ID: " . $this->taskEntity->getId()
			];

		} catch (MethodNotAllowedException $methodNotAllowedException) {
			$response = [
				'error' => self::HTTP_METHOD_ERROR
			];
		} catch (HttpException $httpException) {
			$response = [
				'error' => $httpException->getMessage()
			];
		}

		return new JsonResponse($response);

	}

	/**
	 * Route to list all the tasks in the database
	 *
	 * @Route("api/tasks")
	 * @Method("GET")
	 *
	 * @return JsonResponse
	 */
	public function getTasksAction(): JsonResponse
	{
		$tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
		$tasksData = [];

		foreach ($tasks as $task){
			$tasksData[] = [
				"id" => $task->getId(),
				"task_details" => $task->getTaskDetails()
			];
		}

		return new JsonResponse($tasksData);
	}

	/**
	 * Route to get an existing task
	 *
	 * @Route("/api/tasks/{id}")
	 * @Method("GET")
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getTaskByIdAction(int $id): JsonResponse
	{
		try {
			$entityManager = $this->getDoctrine()->getManager();
			$task = $entityManager->getRepository(Task::class)->find($id);

			if(empty($task)) {
				$response = [
					'error' => 'The task could not be retrieved with the requested ID, please try again.'
				];
			} else {
				$response = [
					'task_details' => $task->getTaskDetails()
				];
			}
		} catch (MethodNotAllowedException $methodNotAllowedException) {
			$response = [
				'error' => self::HTTP_METHOD_ERROR
			];
		} catch (HttpException $httpException) {
			$response = [
				'error' => $httpException->getMessage()
			];
		}

		return new JsonResponse($response);
	}

	/**
	 * Route to edit existing task
	 *
	 * @Route("/api/tasks/{id}")
	 * @Method("PUT")
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function editTaskAction(Request $request, int $id): JsonResponse
	{
		try {
			$requestData = json_decode($request->getContent());

			$entityManager = $this->getDoctrine()->getManager();
			$task = $entityManager->getRepository(Task::class)->find($id);

			if (empty($task)) {
				$response = [
					'error' => 'Task could not be found to edit, please try again.'
				];
			} elseif(empty($requestData->task_details)) {
				$response = [
					'error' => self::REQUEST_EMPTY_ERROR
				];
			} else {
				$task->setTaskDetails($requestData->task_details);
				$entityManager->flush();


				$response = [
					'success' => 'Task with ID: ' . $task->getId() . ' was updated.'
				];
			}
		} catch (MethodNotAllowedException $methodNotAllowedException) {
			$response = [
				'error' => self::HTTP_METHOD_ERROR
			];
		} catch (HttpException $httpException) {
			$response = [
				'error' => $httpException->getMessage()
			];
		}

		return new JsonResponse($response);
	}


	/**
	 * Route to delete an existing task
	 *
	 * @Route("/api/tasks/{id}")
	 * @Method("DELETE")
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function deleteTaskAction(int $id): JsonResponse
	{
		try {
			$entityManager = $this->getDoctrine()->getManager();
			$task = $entityManager->getRepository(Task::class)->find($id);

			if (empty($task)) {
				$response = [
					'error' => 'Task could not be deleted, please try again.'
				];
			} else {
				$entityManager->remove($task);
				$entityManager->flush();

				$response = [
					'success' => 'Task with ID: ' . $id . ' was deleted.'
				];

			}
		} catch (MethodNotAllowedException $methodNotAllowedException) {
			$response = [
				'error' => self::HTTP_METHOD_ERROR
			];
		} catch (HttpException $httpException) {
			$response = [
				'error' => $httpException->getMessage()
			];
		}

		return new JsonResponse($response);
	}
}