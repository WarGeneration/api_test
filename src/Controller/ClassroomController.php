<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomChangeActiveType;
use App\Form\ClassroomType;
use App\Service\ClassroomService;
use Artprima\QueryFilterBundle\Response\Response;
use Artprima\QueryFilterBundle\QueryFilter\QueryFilter as QF;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Artprima\QueryFilterBundle\QueryFilter\Config\ConfigInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Artprima\QueryFilterBundle\Controller\Annotations\QueryFilter;
use FOS\RestBundle\Controller\Annotations as Rest;

class ClassroomController extends AbstractFOSRestController
{
    /**
     * @ParamConverter("config", class="App\QueryFilter\Config\ClassroomConfig",
     *                           converter="query_filter_config_converter",
     *                           options={"entity_class": "App\Entity\Classroom", "repository_method": "findByOrderBy"})
     * @QueryFilter()
     * @Rest\Get("/api/classrooms")
     */
    public function indexAction(ConfigInterface $config)
    {
        $queryFilter = new QF(Response::class);
        $response = $queryFilter->getData($config);
        $view = $this->view([
            'meta' => $response->getMeta(),
            'data' => $response->getData(),
        ], 200);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/api/classrooms/{id}")
     */
    public function showAction(Classroom $classroom)
    {
        return $this->handleView($this->view($classroom));
    }

    /**
     * @Rest\Post("/api/classrooms")
     */
    public function createAction(Request $request, ClassroomService $service)
    {
        $form = $this->createForm(ClassroomType::class);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $data = $form->getData();
            $data = $service->save($data);

            $view = $this->view($data, 201);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @Rest\Patch("/api/classrooms/{id}")
     */
    public function updateAction(Request $request, Classroom $classroom, ClassroomService $service)
    {
        $form = $this->createForm(ClassroomType::class, $classroom);

        $form->submit(json_decode($request->getContent(), true), 'PATCH' !== $request->getMethod());

        if ($form->isValid()) {
            $data = $form->getData();
            $data = $service->save($data);

            $view = $this->view($data, 201);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/api/classrooms/{id}/active")
     */
    public function changeActiveAction(Request $request, Classroom $classroom, ClassroomService $service)
    {
        $form = $this->createForm(ClassroomChangeActiveType::class, $classroom);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $data = $form->getData();
            $data = $service->changeActive($data);

            $view = $this->view($data, 201);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/api/classrooms/{id}")
     */
    public function deleteAction(Classroom $classroom, ClassroomService $service)
    {
        $service->delete($classroom);

        $view = $this->view(null, 204);
        return $this->handleView($view);
    }
}
