<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Status\Status;
use App\Entity\Task;
use App\Service\UuidGenerator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaskController.
 */
class TaskController extends AbstractController
{
    /**
     * @var UuidGenerator
     */
    private $uuidGenerator;

    /**
     * TaskController constructor.
     *
     * @param UuidGenerator $uuidGenerator
     */
    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * List of the posts.
     *
     * @SWG\Tag(name="Task")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of tasks",
     *
     *     @SWG\Schema(
     *     type="array",
     *     @SWG\Items(ref=@Model(type="App\Entity\Task"))
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function tasks(): JsonResponse
    {
        $postRepository = $this->getDoctrine()->getRepository(Task::class);

        return $this->json($postRepository->findAll(), Response::HTTP_OK, [], ['groups' => ['all']]);
    }

    /**
     * Creates new task.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @SWG\Tag(name="Task")
     *
     * @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="title",       type="string", example="First job"),
     *              @SWG\Property(property="description", type="string", example="Job description"),
     *          )
     *      ),
     *
     * @SWG\Response(
     *     response=201,
     *     description="Creates new task",
     *
     *     @Model(type="App\Entity\Task"),
     * )
     */
    public function createTask(Request $request): JsonResponse
    {
        $params = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $todoTask = new Status(Status::TO_DO);
        $todoTask = $this->getDoctrine()->getManager()->merge($todoTask);
        /* @var Status $todoTask */

        $post = new Task(
            $this->uuidGenerator->generate(),
            ($params['title'] ?? ''),
            ($params['description'] ?? ''),
            $todoTask
        );

        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($post, Response::HTTP_CREATED, [], ['groups' => 'all']);
    }

    /**
     * Updates existing task.
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return JsonResponse
     *
     * @SWG\Tag(name="Task")
     *
     * @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="title",       type="string", example="First job"),
     *              @SWG\Property(property="description", type="string", example="Job description"),
     *          )
     *      ),
     *
     * @SWG\Response(
     *     response="200",
     *     description="Updates task",
     *
     *     @SWG\Parameter(name="uuid", type="string", format="uuid"),
     *
     *     @Model(type="App\Entity\Task")
     * )
     *
     * @SWG\Response(
     *     response="204",
     *     description="Task doesnt exist"
     * )
     */
    public function updateTask(Request $request, string $uuid): JsonResponse
    {
        $params = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $task = $this->getDoctrine()->getRepository(Task::class)->find($uuid);

        if (!$task instanceof Task) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        if (isset($params['title'])) {
            $task->changeTitle($params['title']);
        }

        if (isset($params['description'])) {
            $task->changeDescription($params['description']);
        }

        $this->getDoctrine()->getManager()->persist($task);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($task, Response::HTTP_OK, [], ['groups' => 'all']);
    }

    /**
     * Returns task.
     *
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @SWG\Tag(name="Task")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returns task",
     *
     *     @SWG\Parameter(name="uuid", type="string", format="uuid"),
     *
     *     @Model(type="App\Entity\Task")
     * )
     *
     * @SWG\Response(
     *     response="204",
     *     description="Task doesnt exist"
     * )
     */
    public function task(string $uuid): JsonResponse
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($uuid);

        if (!$task instanceof Task) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        return $this->json($task, Response::HTTP_OK, [], ['groups' => 'all']);
    }

    /**
     * @param string $uuid
     * @param string $newStatusId
     *
     * @return JsonResponse
     *
     * @SWG\Tag(name="Task")
     *
     * @SWG\Response(
     *     response="204",
     *     description="Task doesnt exist"
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Changes status of the task",
     *
     *     @SWG\Parameter(name="uuid",        type="string", format="uuid"),
     *     @SWG\Parameter(name="newStatusId", type="string"),
     *
     *     @Model(type="App\Entity\Task")
     * )
     */
    public function changeStatus(string $uuid, string $newStatusId): JsonResponse
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($uuid);
        /* @var Task $task */

        if (!$task instanceof Task) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        $newStatus = new Status($newStatusId);
        $newStatus = $this->getDoctrine()->getManager()->merge($newStatus);
        /* @var Status $newStatus */

        $task->changeStatus($newStatus);

        $this->getDoctrine()->getManager()->persist($task);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($task, Response::HTTP_OK, [], ['groups' => 'all']);
    }
}
