<?php
/**
 * @author matthieu
 * @package Controller
 */

/**
 * Error controller
 * @package Controller
 */
class ErrorController extends Zend_Controller_Action
{

    /**
     * Method called when there is a request error
     */
    public function errorAction()
    {
        $error = $this->_getParam('error_handler');
        switch ($error->type) {
            // 404
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->getResponse()->setHttpResponseCode(404);
                break;
            default:
                $this->getResponse()->setHttpResponseCode(500);
				break;
        }
		$this->view->exception = $error->exception;
		$this->view->request   = $error->request;
    }

}
