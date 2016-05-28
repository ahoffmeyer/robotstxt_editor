<?php

namespace AHoffmeyer\RobotstxtEditor\Utility;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EditorUtility implements SingletonInterface, EditorUtilityInterface
{
    /**
     * @param array $filesarray
     * @param $settings
     * @param $path
     * @return array
     */
    public function getFileModificationTime(array $filesarray, $settings, $path)
    {
        $files = [];

        foreach ($filesarray as $key => $file) {
            $files[$key] = date($settings['list']['timeFormat'], filemtime($path . $file));
        }

        return $files;
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
     * Read the file contents and return them
     *
     * @return string
     */
    public function getContentsFromRobotsTxt($file)
    {
        return GeneralUtility::getUrl($file);
    }

    /**
     * Check if there is already a robots.txt
     *
     * @return bool
     */
    public function checkIfRobotsTxtAlreadyExists($file)
    {
        if ( ! file_exists($file)) {
            return false;
        }

        return true;
    }
}