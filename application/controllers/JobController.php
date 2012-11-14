<?php

use DI\Annotations\Inject;

class JobController extends Zend_Controller_Action
{

	/**
	 * @Inject
	 * @var Zend_Controller_Action_Helper_Json
	 */
	private $jsonHelper;

	public function init() {
		// Resolve dependencies
		\DI\DependencyManager::getInstance()->resolveDependencies($this);
	}

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
				$tubeArray = array();
				// Next job ready
				try {
					/** @var Pheanstalk_Job $job */
					$job = $messageQueue->peekReady($tube);
					$tubeArray[] = array (
						'id'      => $job->getId(),
						'data'    => $job->getData(),
						'status'  => 'ready',
					);
				} catch (Pheanstalk_Exception_ServerException $e) {
					// No job found
				}
				// Next job buried
				try {
					/** @var Pheanstalk_Job $job */
					$job = $messageQueue->peekBuried($tube);
					$tubeArray[] = array (
						'id'      => $job->getId(),
						'data'    => $job->getData(),
						'status'  => 'buried',
					);
				} catch (Pheanstalk_Exception_ServerException $e) {
					// No job found
				}
				$data[$tube] = $tubeArray;
			}
		} catch (Pheanstalk_Exception_ConnectionException $e) {
			$this->getResponse()->setHttpResponseCode(400);
			$data = "Unable to connect to '$server'";
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(500);
			$data = $e->getMessage();
		}
		// Send Json response
		$this->jsonHelper->sendJson($data);
		$this->jsonHelper->getResponse()->sendResponse();
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
		$this->jsonHelper->sendJson($response);
		$this->jsonHelper->getResponse()->sendResponse();
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
		$this->jsonHelper->sendJson($response);
		$this->jsonHelper->getResponse()->sendResponse();
	}

	/**
	 * Bury a job
	 */
	public function buryAction() {
		$server = $this->_getParam("server");
		$tube = $this->_getParam("tube");
		$jobId = $this->_getParam("id");
		try {
			// Connect to the server
			$messageQueue = new Pheanstalk_Pheanstalk($server);
			// Check if the next job in the queue is still the same job
			$stillExists = false;
			try {
				/** @var $nextJob Pheanstalk_Job */
				$nextJob = $messageQueue->peekReady($tube);
				if ($nextJob instanceof Pheanstalk_Job && $nextJob->getId() == $jobId) {
					$stillExists = true;
				}
			} catch (Exception $e) {
			}
			if ($stillExists) {
				// Try to reserve the job
				/** @var $job Pheanstalk_Job */
				$job = $messageQueue->reserveFromTube($tube, 0);
				// Last check (may have changed since first check)
				if ($job instanceof Pheanstalk_Job) {
					// Correct job
					if ($job->getId() == $jobId) {
						$messageQueue->bury($job);
						$response = "";
					} else {
						// Wrong job, un-reserve
						$messageQueue->release($job);
						$response = "There was a error while burying the job, try again.";
					}
				} else {
					$this->getResponse()->setHttpResponseCode(500);
					$response = "No job found in the tube.";
				}
			} else {
				$this->getResponse()->setHttpResponseCode(500);
				$response = "The job can't be found, maybe it has been deleted, delayed or buried.";
			}
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(500);
			$response = $e->getMessage();
		}
		// Send Json response
		$this->jsonHelper->sendJson($response);
		$this->jsonHelper->getResponse()->sendResponse();
	}

	/**
	 * Kick jobs
	 * - server
	 * - tube
	 * - count : number of jobs to kick
	 */
	public function kickAction() {
		$server = $this->_getParam("server");
		$tube = $this->_getParam("tube");
		$count = $this->_getParam("count", 1);
		try {
			// Connect to the server
			$messageQueue = new Pheanstalk_Pheanstalk($server);
			$messageQueue->useTube($tube);
			$messageQueue->kick($count);
			$response = "";
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(500);
			$response = $e->getMessage();
		}
		// Send Json response
		$this->jsonHelper->sendJson($response);
		$this->jsonHelper->getResponse()->sendResponse();
	}

}