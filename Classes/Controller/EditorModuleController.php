<?php

namespace AHoffmeyer\RobotstxtEditor\Controller;

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
     * @var string
     */
    protected $backupPath = '';

    /**
     * Init Action
     */
    public function initializeAction()
    {
        $this->file = PATH_site . self::FILENAME;
        $this->backupPath = PATH_site . $this->settings['backup_path'];
    }

    /**
     * Main view.
     */
    public function indexAction()
    {
        // If robots.txt already exists, get the file contents and push them to the view
        if ($this->checkIfRobotsTxtAlreadyExists()) {
            $contents = $this->getContentsFromRobotsTxt();

            $this->view->assign('contents', $contents);
        }
        // otherwise ask if file should be created
        else {
            $this->view->assign('createFile', true);
        }
    }

    /**
     * Check if there is already a robots.txt
     *
     * @return bool
     */
    public function checkIfRobotsTxtAlreadyExists()
    {
        if ( ! file_exists($this->file)) {
            return false;
        }

        return true;
    }

    /**
     * Read the file contents and return them
     *
     * @return string
     */
    public function getContentsFromRobotsTxt()
    {
        return GeneralUtility::getUrl($this->file);
    }

    /**
     * Create robots.txt if not exists
     * The file existance will be checked above
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction($contents = '')
    {
        // then write the file
        GeneralUtility::writeFile($this->file, $contents);
        // create backup if set
        $this->createBackup();
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
        $this->createAction($this->getContents());
    }

    /**
     * Creates backupfiles of the current robots.txt
     * additionally the backup folder will be created with .htaccess that the folder can't be
     * opened from outside
     */
    public function createBackup()
    {
        if ( ! $this->settings['backup']) {
            return;
        }

        if ( ! is_dir($this->settings['backupPath'])) {
            GeneralUtility::mkdir_deep($this->backupPath);
        }

        $backupFile = $this->backupPath . self::FILENAME . '.bak.'. time() . '.txt';

        GeneralUtility::upload_copy_move($this->file, $backupFile);
        if ( ! file_exists($this->backupPath . '.htaccess')) {
            GeneralUtility::upload_copy_move(__DIR__ . DIRECTORY_SEPARATOR . '_.htaccess', $this->backupPath . '.htaccess');
        }
    }

    /**
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function getContents()
    {
        return strip_tags($this->request->getArgument('contents'));
    }

}