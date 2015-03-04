<?php

namespace Dimando\TodoListBundle\Controller;

use Dimando\TodoListBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TodoListController extends Controller
{
    /**
     * DISPLAY INDEX PAGE WITH ALL TEMPLATES PART
     * @return Response
     */
    public function indexAction()
    {
        $taskManager = $this->get('dimando.gettask');

        return $this->render('DimandoTodoListBundle:Default:index.html.twig', array("tasks" => $taskManager->RenderTask(), "tasks_complete" => $taskManager->RenderTaskComplete()));
    }

    /**
     * @param Task $task
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completeTaskAction(Task $task, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository("DimandoTodoListBundle:Task")->find($task->getId());

        if ($task->getComplete() == 0) {
            $task->setComplete(1);
            $em->persist($task);
            $em->flush();

            $success = "Your task was completed";
            if ($request->isXmlHttpRequest()) {

                return new JsonResponse(array("success" => $success,
                    "tasks" => $this->get('dimando.gettask')->RenderTask(),
                    "tasksComplete" => $this->get('dimando.gettask')->RenderTaskComplete()
                ));
            }

            $this->addFlash("task", $success);
            return $this->redirect($this->generateUrl('dimando_todo_list_homepage'));
        }

        return $this->redirect($this->generateUrl('dimando_todo_list_homepage'));
    }

    /**
     * DISPLAY TASK FORM
     * @return Response
     */
    public function displayTaskFormAction()
    {
        # GET FORM HANDLER SERVICE
        $formHandler = $this->get('dimando.taskform_handler');

        #RETURN THE VIEW
        return $this->render('DimandoTodoListBundle:Task:form.html.twig', array('form' => $formHandler->getForm()->createView()));
    }

    /**
     * ADD AJAX NEW TASK
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createTaskAjaxAction()
    {
        # GET FORM HANDLER SERVICE
        $formHandler = $this->get('dimando.taskform_handler');

        #CHECK IF FORM IS VALID
        if ($formHandler->process() === true){
            return new JsonResponse(array("success" => "Your task was added", "tasks" => $this->get('dimando.gettask')->RenderTask()));
        }else{
            return new JsonResponse(array("error" => "Thank you to fill in all required fields"));
        }
    }

    /**
     * ADD POST NEW TASK
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createTaskAction()
    {
        # GET FORM HANDLER SERVICE
        $formHandler = $this->get('dimando.taskform_handler');

        #CHECK IF FORM IS VALID
        if($formHandler->process() === true){
            $this->addFlash("notice", "<div class='alert alert-success'>Your task was added</div>");
        }else{
            $this->addFlash("notice", "<div class='alert alert-danger'>Thank you to fill in all required fields</div>");
        }

        return $this->redirect($this->generateUrl('dimando_todo_list_homepage'));
    }

}
