<?php

namespace App\Tests\Controller;

use App\Entity\Status\Status;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Tests\RestApiTestCase;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class TaskControllerTest extends RestApiTestCase
{
    /**
     * @var string
     */
    private static $taskId;

    /**
     * @var Generator
     */
    private $faker;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')
            ->getManager();
        $this->taskRepository = $this->entityManager->getRepository(Task::class);

    }

    protected function tearDown()
    {
        $this->entityManager->close();
    }

    /**
     * @covers \App\Controller\TaskController::tasks
     * @covers \App\Controller\TaskController::__construct
     */
    public function testTasks()
    {
        $client = static::createAuthenticatedClient();
        $client->request(Request::METHOD_GET, '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $decodedJson = $this->jsonDecode($client->getResponse());

        $this->assertIsArray($decodedJson);
        $this->assertTask($decodedJson[0]);
    }

    /**
     * @covers \App\Controller\TaskController::createTask
     */
    public function testCreateTask()
    {

        $title = $this->faker->realText(100);
        $description = $this->faker->realText(300);

        $client = static::createAuthenticatedClient();
        $client->request(
            Request::METHOD_POST,
            '/tasks',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => $title,
                    'description' => $description,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $decodedJson = $this->jsonDecode($client->getResponse());
        $this->assertIsArray($decodedJson);
        $this->assertTask($decodedJson);

        self::$taskId = $decodedJson['id'];

        $task = $this->taskRepository->find($decodedJson['id']);
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($description, $task->getDescription());

    }

    /**
     * @covers \App\Controller\TaskController::updateTask
     */
    public function testUpdateTask()
    {
        $newTitle = $this->faker->realText(50);
        $newDescription = $this->faker->realText(300);

        $client = static::createAuthenticatedClient();
        $client->request(
            Request::METHOD_PUT,
            "/tasks/" . self::$taskId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => $newTitle,
                'description' => $newDescription,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $decodedJson = $this->jsonDecode($client->getResponse());
        $this->assertIsArray($decodedJson);
        $this->assertTask($decodedJson);
        $this->assertSame($newTitle, $decodedJson['title']);
        $this->assertSame($newDescription, $decodedJson['description']);

        $task = $this->taskRepository->find(self::$taskId);
        $this->assertSame($newTitle, $task->getTitle());
        $this->assertSame($newDescription, $task->getDescription());
    }

    /**
     * @covers \App\Controller\TaskController::task
     */
    public function testTask()
    {
        $client = static::createAuthenticatedClient();
        $client->request(
            Request::METHOD_GET,
            "/tasks/" . self::$taskId
        );

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $decodedJson = $this->jsonDecode($client->getResponse());
        $this->assertIsArray($decodedJson);
        $this->assertTask($decodedJson);
        $this->assertSame(self::$taskId, $decodedJson['id']);
    }

    /**
     * @covers \App\Controller\TaskController::changeStatus
     */
    public function testChangeStatus()
    {
        $client = static::createAuthenticatedClient();
        $client->request(
            Request::METHOD_PUT,
            '/tasks/' . self::$taskId . '/status/' . Status::IN_PROGRESS
        );

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $decodedJson = $this->jsonDecode($client->getResponse());
        $this->assertIsArray($decodedJson);
        $this->assertTask($decodedJson);

        $this->assertSame(Status::IN_PROGRESS, $decodedJson['status']['id']);

        $task = $this->taskRepository->find(self::$taskId);
        $this->assertSame(Status::IN_PROGRESS, $task->getStatus()->getId());
    }

    /**
     * @param $decodedJson
     */
    private function assertTask($decodedJson): void
    {
        $this->assertArrayHasKey('id', $decodedJson);
        $this->assertArrayHasKey('title', $decodedJson);
        $this->assertArrayHasKey('description', $decodedJson);
        $this->assertArrayHasKey('status', $decodedJson);
    }
}
