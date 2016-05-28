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

interface EditorUtilityInterface
{
    /**
     * @param array $filesarray
     * @param mixed $settings
     * @param string $path
     * @return array
     */
    public function getFileModificationTime(array $filesarray, $settings, $path);

    /**
     * @param array $files
     * @param array $times
     * @return mixed
     */
    public function mergeFileAndTimeArray(array $files, array $times);

    /**
     * @param string $file
     * @return string
     */
    public function getContentsFromRobotsTxt($file);

    /**
     * @param string $file
     * @return mixed
     */
    public function checkIfRobotsTxtAlreadyExists($file);
}