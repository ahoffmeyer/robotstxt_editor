<?php

namespace AHoffmeyer\RobotstxtEditor\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use AHoffmeyer\RobotstxtEditor\Utility\EditorUtilityInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class EditorModuleController extends ActionController
{

    const FILENAME = 'robots.txt';

    /**
     * @var string
     */
    protected $file = '';

    /**
     * @var array
     */
    protected $backupFiles = [];

    /**
     * @var string
     */
    protected $backupPath = '';

    /**
     * @var EditorUtilityInterface
     */
    protected $editorUtility = null;

    /**
     * @var PageRenderer
     */
    protected $pageRenderer = null;

    /**
     * @var ObjectManager
     */
    protected $objectManager = null;

    /**
     * @param EditorUtilityInterface $editorUtility
     */
    public function injectEditorUtilityInterface(EditorUtilityInterface $editorUtility)
    {
        $this->editorUtility = $editorUtility;
    }

    /**
     * Init Action
     */
    public function initializeAction()
    {
        $this->file = PATH_site . self::FILENAME;
        $this->backupPath = PATH_site . $this->settings['backup_path'];
        $this->backupFiles = GeneralUtility::getFilesInDir($this->backupPath, 'txt');
    }

    /**
     * Main view.
     */
    public function indexAction()
    {
        // If robots.txt already exists, get the file contents and push them to the view
        if ($this->editorUtility->checkIfRobotsTxtAlreadyExists($this->file)) {
            $contents = $this->editorUtility->getContentsFromRobotsTxt($this->file);

            $this->view->assign('contents', $contents);
        }
        // otherwise ask if file should be created
        else {
            $this->view->assign('createFile', [
                true, ['files' => $this->backupFiles]
            ]);
        }
    }

    /**
     * Create robots.txt if not exists
     * The file existance will be checked above
     *
     * @param string $contents
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction($contents = '')
    {
        // then write the file
        GeneralUtility::writeFile($this->file, $contents);
        // create backup if set
        if ($this->request->hasArgument('backup')) {
            $this->createBackup();
        }
        // after all redirect to the beginning
        $this->redirect('index');
    }

    /**
     * Delete robots.txt
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function deleteAction()
    {
        unlink($this->file);

        $this->redirect('index');
    }

    /**
     * Writes new content in file
     */
    public function updateAction()
    {
        $contents = strip_tags($this->request->getArgument('contents'));

        $this->createAction($contents);
    }

    /**
     * Creates backupfiles of the current robots.txt
     * additionally the backup folder will be created with .htaccess that the folder can't be
     * opened from outside
     */
    public function createBackup()
    {
        // if there is no TS setting, then nothing should be done
        if ( ! $this->settings['backup']) {
            return;
        }

        // check if the checkbox is set
        if ( ! $this->request->getArgument('backup')) {
            return;
        }

        // create the path to the backupdir if it's not there already
        if ( ! is_dir($this->settings['backupPath'])) {
            GeneralUtility::mkdir_deep($this->backupPath);
        }

        $backupFile = $this->backupPath . self::FILENAME . '.bak.'. time() . '.txt';

        GeneralUtility::upload_copy_move($this->file, $backupFile);

        if ( ! file_exists($this->backupPath . '.htaccess')) {
            GeneralUtility::upload_copy_move(__DIR__ . DIRECTORY_SEPARATOR . 'Assets' . DIRECTORY_SEPARATOR .'_.htaccess', $this->backupPath . '.htaccess');
        }
    }

}