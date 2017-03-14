<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class OrdersController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        if (empty($this->persistent->searchParams)) {
            $this->persistent->searchParams = array('searchType' => 'A', 'terms' => '');
        }
        return $this->dispatcher->forward(
            [
                'controller' => "orders",
                'action' => 'search'
            ]
        );
    }

    /**
     * Searches for orders
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost() && $this->request->hasPost('searchType')) {
            // Create the query conditions
            $this->persistent->searchParams = $this->request->getPost();
        } else {
            // Paginate using the existing conditions
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->searchParams;

        // prepare date interval for searching
        switch($parameters['searchType']) {
            case 'L':
                $parameters['date'] = 'SUBDATE(NOW(), 7)';
                break;
            case 'T':
                $parameters['date'] = 'DATE(NOW())';
                break;
            case 'A':
            default:
                $parameters['date'] = '0';
        }

        // actual search
        $orders = Orders::find($parameters);

        if (count($orders) == 0) {
            $this->flash->notice("The search did not find any orders");
        }

        $paginator = new Paginator([
            'data' => $orders,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $searchForm = new SearchForm();
        $searchForm->bind($this->persistent->searchParams, new Search());

        $this->view->form = $searchForm;
        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->view->form = new OrdersForm(null, array('edit' => true));
    }

    /**
     * Edits a order
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $order = Orders::findFirstByid($id);

            if (!$order) {
                $this->flash->error(
                    "Order was not found"
                );

                return $this->dispatcher->forward(
                    [
                        "controller" => "orders",
                        "action"     => "index",
                    ]
                );
            }

            $this->view->form = new OrdersForm(
                $order,
                [
                    "edit" => true,
                ]
            );
        }
    }

    /**
     * Creates a new order
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    'controller' => "orders",
                    'action' => 'index'
                ]
            );
        }

        $form = new OrdersForm();

        $order = new Orders();

        // Validate the input
        $data = $this->request->getPost();

        if (!$form->isValid($data, $order)) {
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "orders",
                    "action"     => "new",
                ]
            );
        }

        // store the order
        if (!$order->save()) {
            $messages = $order->getMessages();

            foreach ($messages as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'new'
            ]);

            return;
        }

        $form->clear();
        $this->persistent->searchParams = null;

        $this->flash->success("Order was created successfully");

        return $this->dispatcher->forward([
            'controller' => "orders",
            'action' => 'index'
        ]);
    }

    /**
     * Saves an edited order
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    'controller' => "orders",
                    'action' => 'index'
                ]
            );
        }

        $id = $this->request->getPost("id", "int");

        $order = Orders::findFirstByid($id);

        if (!$order) {
            $this->flash->error("Order does not exist " . $id);

            return $this->dispatcher->forward(
                [
                    'controller' => "orders",
                    'action' => 'index'
                ]
            );
        }

        $form = new OrdersForm();

        $data = $this->request->getPost();

        // validate the input
        if (!$form->isValid($data, $order)) {
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "orders",
                    "action"     => "index",
                ]
            );
        }

        // update the order
        if (!$order->save()) {
            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    'controller' => "orders",
                    'action' => 'edit',
                    'params' => [$order->id]
                ]
            );
        }

        $form->clear();

        $this->flash->success("Order was updated successfully");

        return $this->dispatcher->forward(
            [
                'controller' => "orders",
                'action' => 'index'
            ]
        );
    }

    /**
     * Deletes a order
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $order = Orders::findFirstByid($id);
        if (!$order) {
            $this->flash->error("Order was not found");

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'index'
            ]);

            return;
        }

        if (!$order->delete()) {
            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Order was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "orders",
            'action' => "index"
        ]);
    }

}
