wp2moodle ws completion
=======================

This is a moodle XMLPRC webservice for returning completion data about users who have enrolled using the wp2moodle plugin suite.

Requirements:
-------------
Requires the Certificate plugin for moodle to be installed already, and of course the wp2moodle plugins.

https://github.com/markn86/moodle-mod_certificate
https://github.com/frumbert/wp2moodle-moodle

Installation
------------

1. Download the repository (+unzip) and rename it so its folder is called 'wscompletion'
2. Put it in your /moodle/local/ folder
3. Activate the plugin
4. Make sure web serivices are enabled, XMLRPC is running and enabled

Usage / example
---------------

I'm not going to explain to you how to set up and use webservices in moodle (sorry).

wp2moodle contains a file called token.php which will generate an authentication token for the user. This allows a user access to a specific function call in a web service, which is contained in this plugin.

see the client/demo.php file for a simple page that lets you test the service call, or client/client.php for a coders example on what is going on (yes, read the php!)

License
-------

"Here you go, have it."
I think that's the MIT License. Or Public Domain. Pick whatever suits you.