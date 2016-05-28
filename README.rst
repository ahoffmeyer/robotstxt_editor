robots.txt editor
=================

A easy to use robots.txt editor. Create, update, delete, backupd or restore backups directly within your TYPO3 backend.

Installation
------------

Using composer

    $> composer require ahoffmeyer/robotstxt_editor

Then activate it via Extension Manager.

Usage
-----

The editor will take place under your **Admin Tools**.
If there is no robots.txt file on your document root, you can create one by clicking on **Create robots.txt**

Afterwards you can edit the contents of your robots.txt.

Note: If you check **Save backupfile** a copy of the current robots.txt will be created and stored in the **robots.bak** folder.
You can change the backup folder by editing the setup.txt file of the extension.

Backups
=======

Click on **List backups** and you can see all your backup files available.

You can now delete, restore or simply preview its contents.

Restore Backup
--------------

Restore a backup file by simply clicking on **restore**

This will create a backup file of the current robots.txt (with special filename) and set the backup file as new robots.txt into your document root.

Author
======

Any questions or pull requests don't hesitate:

Andreas Hoffmeyer <hallo@andreas-hoffmeyer.de>

GitHub: https://github.com/ahoffmeyer/robotstxt_editor

Website https://www.andreas-hoffmeyer.de