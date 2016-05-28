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

use TYPO3\CMS\Core\Error\Exception;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackupModuleController extends EditorModuleController
{

    /**
     * list files and file mod times in view
     */
    public function listAction()
    {
        $files = $this->backupFiles;
        $times = $this->editorUtility->getFileModificationTime($files, $this->settings, $this->backupPath);

        $this->view->assign('files',  $this->editorUtility->mergeFileAndTimeArray($files,  $times));
    }

    /**
     * @param string $file
     * @throws Exception
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function removeAction($file)
    {
        if ( ! unlink($this->backupPath . $file)) {
            throw new Exception('File coiuld not be deleted');
        }

        $this->addFlashMessage("File {$file} was deleted from system");

        $this->redirect('list');
    }

    /**
     * @param string $file
     */
    public function restoreAction($file)
    {
        $restore = $this->backupPath . $file;
        // first of all, save the old robots txt
        $backupFile = $this->backupPath . self::FILENAME . '-restored.bak.'. time() . '.txt';
        GeneralUtility::upload_copy_move($this->file, $backupFile);

        // then remove the current robots.txt
        if (file_exists($this->file)) {
            unlink($this->file);
        }

        // at the end copy the restore file to root dir
        GeneralUtility::upload_copy_move($restore, $this->file);

        $this->redirect('index', 'EditorModule');
    }

    /**
     * @param array $params
     * @param AjaxRequestHandler|NULL $ajaxObj
     * @return string
     */
    public function checkFileContentAction($params = [], AjaxRequestHandler &$ajaxObj = null)
    {
        $file = $params['request']->getQueryParams();
        $checkFile = PATH_site . $file['data'];
        $ajaxObj->addContent('editorFileContent', nl2br(file_get_contents($checkFile)));
    }
}