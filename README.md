# robots.txt editor

A simple TYPO3 backend module to edit a robots.txt file directly within TYPO3.

## What it does

The extension provides a TYPO3 backend module to create, edit, delete, backup and restore backupds of existing or new robots.txt files.

## Installation

Simply add to composer.json **ahoffmeyer/robotstxt-editor** and install it via Extension Manager.
Or require it to your project:

    $> composer require ahoffmeyer/robotstxt-editor
    
## Configuration

You can adjust some settings using TypoScript. Keep in mind, that the extension uses TypoScript loaded globally.

The following TypoScript can be adjusted to your needs:

    module.tx_robotstxteditor {
        settings {
            # CSS file for nice styles
            cssFile = /typo3conf/ext/robotstxt_editor/Resources/Public/Stylesheet/main.css
            
            # Should a backup possible in the end
            backup = 1
            
            # you can even change the path
            # Notice: The path MUST be relative to your TYPO3 root directory
            backup_path = robots.bak/
    
            # Settings for the backup list view
            # should the creation time displayed - for better check up
            list.showModificationTime = 1
            
            # change the time format
            list.stimeFormat = H:i:s - d.m.y            
        }
    }