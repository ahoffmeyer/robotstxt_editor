<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	ExtensionUtility::registerModule(
		'AHoffmeyer.' . $_EXTKEY,
		'tools',
		'editorModule',
		'',
		array(
			'EditorModule' => 'index, create, delete, update',
			'BackupModule' => 'list, remove, restore, check',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/thumbs.svg',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_editor.xlf',
		)
	);

	ExtensionManagementUtility::registerAjaxHandler (
		'BackupModuleController::checkFileContent',
		'AHoffmeyer\\RobotstxtEditor\\Controller\\BackupModuleController->checkFileContentAction'
	);

}