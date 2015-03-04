<?php

namespace Dimando\TodoListBundle\Services;

use Doctrine\ORM\EntityManager;

class TasksPart {

    private $em;
    private $twig;

    /**
     * @param EntityManager $em
     * @param \Twig_Environment $twig
     */
    public function __construct(EntityManager $em, \Twig_Environment $twig )
    {
        $this->em = $em->getRepository('DimandoTodoListBundle:Task');
        $this->twig = $twig;
    }

    /**
     * RENDER TASK TEMPLATE
     * @return string
     */
    public function RenderTask(){

        $tasks = $this->em->findBy(array('complete' => 0), array('priority' => 'DESC' ,'createdAt' => 'DESC'), 3);

        return $this->twig->render('DimandoTodoListBundle:Task:list_task.html.twig', array('tasks' => $tasks));
    }

    /**
     * RENDER TASK COMPLETE TEAMPLATE
     * @return string
     */
    public function RenderTaskComplete(){

        $tasks_complete = $this->em->findBy(array('complete' => 1), array('updatedAt' => 'DESC'), 3);

        return $this->twig->render('DimandoTodoListBundle:Task:list_task_complete.html.twig', array('tasks_complete' => $tasks_complete));
    }
}
