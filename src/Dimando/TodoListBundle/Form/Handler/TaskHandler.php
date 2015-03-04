<?php

namespace Dimando\TodoListBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class TaskHandler{

    protected $form;
    protected $request;
    protected $em;
    private $datas;

    /**
     * @param Form $form
     * @param Request $request
     * @param EntityManager $em
     */
    public function __construct(Form $form, Request $request, EntityManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    /**
     * HANDLE REQUEST
     */
    private function handleFormRequest(){

        $this->form->handleRequest($this->request);
        $this->datas = $this->form->getData();

    }

    /**
     * POST / AJAX PROCESS
     * @return bool
     */
    public function process()
    {
        $this->handleFormRequest();

        if($this->request->isMethod('post') || $this->request->isXmlHttpRequest())
        {
            if($this->form->isValid()) {
                $this->onSuccess();
                return true;
            }else{
                if($this->request->isMethod('post')){
                    return 'error';
                }
                return false;
            }
        }else{
            return false;
        }

    }

    /**
     * PERSIST TASK OBJECT
     */
    protected function onSuccess()
    {
        $this->em->persist($this->form->getData());
        $this->em->flush();

        return true;
    }

    /**
     * GET FORM
     * @return Form
     */
    public function getForm(){
        return $this->form;
    }
}