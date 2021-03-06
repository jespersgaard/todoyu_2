<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Task with timetracking extension
 *
 * @package		Todoyu
 * @subpackage	Timetracking
 */
class TodoyuTimetrackingTask extends TodoyuProjectTask {

	/**
	 * Get sum of tracked time of the task
	 *
	 * @param	Boolean		$checkChargeable
	 * @return	Integer		 Seconds
	 */
	public function getTrackedTime($checkChargeable = false) {
		return TodoyuTimetracking::getTrackedTaskTimeTotal($this->getID(), $checkChargeable);
	}



	/**
	 * Get amount of chargeable time of the task
	 *
	 * @return	Integer
	 */
	public function getChargeableTime() {
		return $this->getTrackedTime(true);
	}



	/**
	 * Get open time
	 * Difference between estimated and tracked time, but only when positive
	 * If more time than estimated was tracked, the open workload is 0 nevertheless
	 *
	 * @return	Integer
	 */
	public function getRemainingWorkload() {
		$openTime	= $this->getEstimatedWorkload() - $this->getTrackedTime();

		return $openTime > 0 ? $openTime : 0;
	}



	/**
	 * Get remaining workload
	 *
	 * @depreceated
	 * @see		getRemainingWorkload
	 * @return	Integer
	 */
	public function getOpenWorkload() {
		return $this->getRemainingWorkload();
	}



	/**
	 * Check whether tracking is currently running for this task
	 *
	 * @return	Boolean
	 */
	public function isRunning() {
		return TodoyuTimetracking::isTaskRunning($this->getID());
	}

}

?>