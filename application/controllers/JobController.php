<?php

class JobController extends Zend_Controller_Action
{

	/**
	 * Returns the list of the jobs in queue for the server
	 */
	public function getListAction() {
		$server = $this->_getParam("server");
		$data = array ();
		try {
			// Connect to the server and get job list
			$messageQueue = new Pheanstalk_Pheanstalk($server);
			$tubes = $messageQueue->listTubes();
			foreach ($tubes as $tube) {
				try {
					/** @var Pheanstalk_Job $job */
					$job = $messageQueue->peekReady($tube);
					$data[] = array (
						'tube'    => $tube,
						'id'      => $job->getId(),
						'data'    => $job->getData(),
						'status'  => 'ready',
					);
				} catch (Pheanstalk_Exception_ServerException $e) {
					$data[] = array (
						'tube'   => $tube,
						'id'     => '',
						'data'   => $e->getMessage(),
						'status' => 'ready',
					);
				}
			}
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(500);
			$data = $e->getMessage();
		}
		// Send Json response
		$jsonHelper = $this->getHelper('Json');
		$jsonHelper->sendJson($data);
		$jsonHelper->getResponse()->sendResponse();
	}

	/**
	 * Add a job in the server queue
	 */
	public function addAction() {
		$server = $this->_getParam("server");
		$data = $this->_getParam("data");
		$tube = $this->_getParam("tube");
		try {
			if (!$data) {
				throw new Exception("The data field must not be empty");
			}
			if (!$tube) {
				throw new Exception("The tube field must not be empty");
			}
			// Connect to the server
			$messageQueue = new Pheanstalk_Pheanstalk($server);
			$messageQueue->useTube($tube);
			$messageQueue->put($data);
			$response = "";
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(500);
			$response = $e->getMessage();
		}
		// Send Json response
		$jsonHelper = $this->getHelper('Json');
		$jsonHelper->sendJson($response);
		$jsonHelper->getResponse()->sendResponse();
	}

	/**
	 * Delete a job
	 */
	public function deleteAction() {
		$server = $this->_getParam("server");
		$jobId = $this->_getParam("id");
		try {
			// Connect to the server
			$messageQueue = new Pheanstalk_Pheanstalk($server);
			$job = $messageQueue->peek($jobId);
			$messageQueue->delete($job);
			$response = "";
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(500);
			$response = $e->getMessage();
		}
		// Send Json response
		$jsonHelper = $this->getHelper('Json');
		$jsonHelper->sendJson($response);
		$jsonHelper->getResponse()->sendResponse();
	}

}