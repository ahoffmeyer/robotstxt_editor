<?php

namespace AHoffmeyer\RobotstxtEditor\Utility;

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