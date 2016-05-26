<?php

namespace AHoffmeyer\RobotstxtEditor\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Error\Exception;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
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
     * list files and file mod times in view
     */
    public function listAction()
    {
        $files = GeneralUtility::getFilesInDir($this->backupPath, 'txt');
        $times = $this->getFileModificationTime($files);

        $this->view->assign('files',  $this->mergeFileAndTimeArray($files,  $times));
    }

    /**
     * @param array $files
     * @param array $times
     * @return array
     */
    public function mergeFileAndTimeArray(array $files, array $times)
    {
        $result = [];

        foreach ($files as $key => $file) {
            $result[] = [
                'name' => $file,
                'time' => $times[$key]
            ];
        }

        return $result;
    }

    /**
     * @param array $filesarray
     * @return array
     */
    public function getFileModificationTime(array $filesarray)
    {
        $files = [];

        foreach ($filesarray as $key => $file) {
            $files[$key] = date($this->settings['list']['timeFormat'], filemtime($this->backupPath . $file));
        }

        return $files;
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
        $this->createAction($this->getContents());
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

    /**
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function getContents()
    {
        return strip_tags($this->request->getArgument('contents'));
    }

    /**
     * @param $file
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
     * @param array $params
     * @param AjaxRequestHandler|NULL $ajaxObj
     * @return string
     */
    public function checkFileContent($params = [], AjaxRequestHandler &$ajaxObj = null)
    {
        $file = $params['request']->getQueryParams();
        $checkFile = PATH_site . $file['data'];
        $ajaxObj->addContent('editorFileContent', nl2br(file_get_contents($checkFile)));
    }

}