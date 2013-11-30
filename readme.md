wp2moodle ws completion
=======================

This is a moodle webservice for returning completion data about users who have enrolled using the wp2moodle Single Sign On plugin.

Requirements:
-------------
- Moodle needs to know which protocols your webservice is using. E.g. SOAP, REST or XMLRPC. You have to enable these.
- You need to add webservice/rest:use or webservice/xmlrpc:use to the role of user that will be connecting. This is generally Authenticated User or Student.
- Utilises token.php portion of wp2moodle single sign on plugin (https://github.com/frumbert/wp2moodle-moodle).

Optional:
---------
If the Moodle Certifiacate plugin has been installed prior to this plugin being installed, it will return the row ids / pickup codes of certificates generated for that plugin. (https://github.com/markn86/moodle-mod_certificate)

Installation
------------

1. Download the repository (+unzip) and rename it so its folder is called 'wscompletion'
2. Put it in your /moodle/local/ folder
3. Activate the plugin
4. Nothing to configure!

Usage / example
---------------

1. Enable moodle web services and the protocol you want to use (e.g. REST), and.
2. Make sure that webservice/rest:use (or webservice/xmlrpc:use) is allowed for authenticated user / student role
3. Authenticate a user across from Wordpress to Moodle using the wp2moodle plugin.
4. Generate tokens for this user (using /auth/wp2moodle/token.php) to allow the account to log in via a webservice
5. Call the web service as that user from the server that hosts the wp2moodle wordpress.
6. Process the results in some way to make it nice.

There's a demo script in the ~/local/wscompeltion/client/ folder. You'll need to nose around in these php files, but I've documented them.
 - demo.php lets you try stuff easily
 - client.rest.php has an example on how to encode and decode xmlrpc
 - client.json.php has an example that returns results as JSON (supported by REST in Moodle 2.2+)

License
-------

"Here you go, have it."
I think that's the MIT License. Or Public Domain.