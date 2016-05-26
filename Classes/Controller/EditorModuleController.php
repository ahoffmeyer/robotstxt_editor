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
     * Init Action
     */
    public function initializeAction()
    {
        $this->file = PATH_site . DIRECTORY_SEPARATOR . self::FILENAME;
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
    public function createAction()
    {
        // then write the file
        GeneralUtility::writeFile($this->file, '');
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

        GeneralUtility::writeFile($this->file, $contents);

        $this->redirect('index');
    }

}