<?php

namespace App\Controller;

use App\Entity\Task;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     *
     * @SWG\Response(
     *     response=201,
     *     description="Creates new task",
     *
     *     @SWG\Parameter(name="uuid", type="string", format="uuid"),
     *
     *     @Model(type="App\Entity\Task"),
     * )
     *
     * @param Request $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function createPost(Request $request, string $uuid): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $post = new Task($uuid, $params['title'], $params['description']);

        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($post, Response::HTTP_CREATED);
    }

}
