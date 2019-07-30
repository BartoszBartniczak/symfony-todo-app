<?php

namespace App\Controller;

use App\Entity\Task;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function posts(): JsonResponse
    {
        $postRepository = $this->getDoctrine()->getRepository(Task::class);

        return $this->json(
            $postRepository->findAll()
        );
    }

    /**
     * Creates new task
     *
     * @SWG\Tag(name="Task")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Creates new task",
     *
     *     @Model(type="App\Entity\Task"),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPost(Request $request): JsonResponse
    {
        $params = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $post = new Task(
            Uuid::uuid4(),
            $params['title'] ?? '',
            $params['description'] ?? ''
        );

        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($post, Response::HTTP_CREATED);
    }

    /**
     * Updates existing task
     *
     * @SWG\Tag(name="Task")
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
     *
     * @param Request $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function updatePost(Request $request, string $uuid): JsonResponse
    {
        $params = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $task = $this->getDoctrine()->getRepository(Task::class)->find($uuid);

        if (!$task instanceof Task) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        $task->setTitle($params['title']);
        $task->setDescription($params['description']);

        $this->getDoctrine()->getManager()->persist($task);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($task, Response::HTTP_OK);
    }

}
