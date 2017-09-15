# Servershow
Simple one page servermonitor in php
## Purpose
My development and testing environments always consist of
a virtual server (based on virtualbox - vagrant - ubuntu/debian) running
docker. Within the docker containter the database, webserver 
and other services will run. It is practical to see in a quick
glance if everything is running smoothly.

My acceptance tests are done on a VPS with docker containers, it
is always handy to have something available that shows
it is running. 

I want a simple system, easy to install, easy to tweak for
specific purposes. No installation or browsing through all
kinds of documentation, so I wrote it myself.
## Installation
Copy the monitor directory to your webserver. 
## Limitations
The system is Linux only, and needs a working webserver with 
PHP installed (5.3.28+).

It relies heavily on access to the /proc directory. If that directory is not 
accessible (mostly due to basedir limitations used by shared hosters), the information
is very limited (I can only use what php is able to tell). 
## Components
The system consists of four parts:
* UI

    The userinterface is based on a flexbox. The contents are updated
    every few seconds. It uses jquery, which is bundled with this software
    
* api

    The api produces json messages with information about the system
    
* core
        
    The core is reponsible for making the state of the system available
    
* lib

    Common functions/classes with generic methods, including the
session handling and autoloader.
    
## Security
The api is only accessible if it is called from the same session that
started the UI (a.k.a. the index.php). If it is not called from
the same session, it will return a empty json message.
    

## Adding extra blocks
You can add more blocks with information. Use the following cookbook:
1. Add the necessary method to get system information to core/linux.php
2. Define a template block in ui/template
3. Add the template block to the $blocks array in the index.php file. You can choose three
kinds of boxes. Superboxes are full width. Large boxes are put before small boxes on the screen.
4. Add an api function. Make sure that the name of the field in the json message
matches the id as used in the template file.
5. Add a updater function to ui/js/updater.js.
6. If you have a special (more than just a simple value in a table), build the
dedicated functions into the ui/js/updater.js.

 

