<?php

class IndexController extends Zend_Controller_Action
{

	public function indexAction() {
		$this->view->server = $this->_getParam("server", "localhost:11300");
	}

	public function aboutAction() {
	}

}