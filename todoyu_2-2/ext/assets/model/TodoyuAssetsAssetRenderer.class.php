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
 * Renderer for assets
 *
 * @package		Todoyu
 * @subpackage	Assets
 */
class TodoyuAssetsAssetRenderer {

	/**
	 * Render content for the task tab
	 *
	 * @param	Integer		$idTask
	 * @return	String
	 */
	public static function renderTabContent($idTask) {
		Todoyu::restrict('assets', 'general:use');

		$idTask		= intval($idTask);
		$numAssets	= TodoyuAssetsAssetManager::getNumTaskAssets($idTask);
		$hasAssets	= $numAssets > 0;
		$locked		= TodoyuProjectTaskManager::isLocked($idTask);

		if( $locked ) {
			if( !$hasAssets ) {
				$content = self::renderLockedMessage();
			} else {
				$content = self::renderList($idTask);
			}
		} else {
			$content = self::renderListControl($idTask);

			if( $hasAssets ) {
				$content .= self::renderList($idTask);
			}
		}

		return $content;
	}



	/**
	 * Render locked message
	 *
	 * @return	String
	 */
	public static function renderLockedMessage() {
		$tmpl	= 'ext/comment/view/locked.tmpl';

		return Todoyu::render($tmpl);
	}




	/**
	 * Render asset list view
	 *
	 * @param	Integer		$idTask
	 * @return	String
	 */
	public static function renderList($idTask) {
		$idTask	= intval($idTask);

		$tmpl	= 'ext/assets/view/list.tmpl';
		$data	= array(
			'idTask'	=> $idTask,
			'assets'	=> TodoyuAssetsAssetManager::getTaskAssets($idTask)
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render list control elements
	 *
	 * @param	Integer		$idTask
	 * @return	String
	 */
	public static function renderListControl($idTask) {
		$idTask	= intval($idTask);

		$tmpl	= 'ext/assets/view/list-control.tmpl';
		$data	= array(
			'idTask'	=> $idTask
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render upload-frame content
	 *
	 * @param	Integer		$idTask
	 * @param	String		$fileName
	 * @return	String
	 */
	public static function renderUploadframeContent($idTask, $fileName) {
		$idTask		= intval($idTask);
		$tabLabel	= TodoyuAssetsTaskAssetViewHelper::getTabLabel($idTask);

		$tmpl	= 'core/view/htmldoc.tmpl';
		$data	= array(
			'title'		=> 'Uploader IFrame',
			'content'	=> TodoyuString::wrapScript('window.parent.Todoyu.Ext.assets.Upload.uploadFinished(' . $idTask . ', \'' . $tabLabel . '\');')
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render upload-frame content if upload has failed
	 *
	 * @param	Integer		$idTask
	 * @param	Integer		$error
	 * @param	String		$fileName
	 * @return	String
	 */
	public static function renderUploadframeContentFailed($idTask, $error, $fileName) {
		$error		= intval($error);
		$maxFileSize= TodoyuString::formatSize(intval(Todoyu::$CONFIG['EXT']['assets']['max_file_size']));

		$commands	= 'window.parent.Todoyu.Ext.assets.Upload.uploadFailed(' . $idTask . ', ' . $error . ', \'' . $fileName . '\', \'' . $maxFileSize . '\');';

		return TodoyuRenderer::renderUploadIFrameJsContent($commands);
	}

}

?>