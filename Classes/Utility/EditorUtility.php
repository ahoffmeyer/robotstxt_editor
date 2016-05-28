<?php

namespace AHoffmeyer\RobotstxtEditor\Utility;

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