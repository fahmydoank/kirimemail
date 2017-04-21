<?php

use \Phalcon\Tag as Tag,
    \Phalcon\Mvc\Model\Criteria;

class SubscribesController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Subscribes", $_POST);
            $this->session->conditions = $query->getConditions();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
            if ($numberPage <= 0) {
                $numberPage = 1;
            }
        }
		var_dump($this->session->conditions);

        $parameters = array();
        /*if ($this->session->conditions) {
            $parameters["conditions"] = $this->session->conditions;
        }*/
        $parameters["order"] = "id";

        $subscribes = Subscribes::find($parameters);
        if (count($subscribes) == 0) {
            $this->flash->notice("The search did not find any subscribes");
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $subscribes,
            "limit"=> 10,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);
    }

    public function searchAction()
    {
		$this->session->conditions = null;
    }

    public function newAction()
    {
		
    }

    public function editAction($id)
    {

        $request = $this->request;
        if (!$request->isPost()) {

            $subscribes = Subscribes::findFirst(array(
                'id = :id:',
                'bind' => array('id' => $id)
            ));
            if (!$subscribes) {
                $this->flash->error("The subscribe was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "subscribes",
                    "action" => "index"
                ));
            }
            $this->view->setVar("id", $subscribes->id);

            Tag::displayTo("id", $subscribes->id);
            Tag::displayTo("name", $subscribes->name);
            Tag::displayTo("email", $subscribes->email);
        }
    }

    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

        $subscribes = new Subscribes();
        $subscribes->id = $this->request->getPost("id");
        $subscribes->name = $this->request->getPost("name");
        $subscribes->email = $this->request->getPost("email");
        if (!$subscribes->save()) {
            foreach ($subscribes->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "new"
            ));
        } else {
            $this->flash->success("The subscribe was created successfully");
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

        $subscribes = Subscribes::findFirst(array(
            'id = :id:',
            'bind' => array('id' => $this->request->getPost("id"))
        ));
        if (!$subscribes) {
            $this->flash->error("The subscribe does not exist");
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

        $subscribes->id = $this->request->getPost("id");
        $subscribes->name = $this->request->getPost("name");
        $subscribes->email = $this->request->getPost("email");

        if (!$subscribes->save()) {
            foreach ($subscribes->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "edit",
                "params" => array($subscribes->id)
            ));
        } else {
            $this->flash->success("subscribes was updated successfully");
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

    }

    public function deleteAction($id)
    {

        $subscribes = Subscribes::findFirst(array(
            'id = :id:',
            'bind' => array('id' => $id)
        ));
        if (!$subscribes) {
            $this->flash->error("The subscribe was not found");
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }

        if (!$subscribes->delete()) {
            foreach ($subscribes->getMessages() as $message){
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "search"
            ));
        } else {
            $this->flash->success("The subscribe was deleted");
            return $this->dispatcher->forward(array(
                "controller" => "subscribes",
                "action" => "index"
            ));
        }
    }

}
