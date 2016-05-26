<?php
use AHoffmeyer\RobotstxtEditor\Controller\EditorModuleController;

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'AHoffmeyer.' . $_EXTKEY,
		'tools',
		'editorModule',
		'',
		array(
			'EditorModule' => 'index, create, delete, update',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_editor.xlf',
		)
	);

}