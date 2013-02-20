Bugsnag
=======

Integrate with http://bugsnag.com

Once installed and configured, this module will report PHP errors and
exceptions to Bugsnag. It will also report watchdog errors and above.


Installation
------------

1. Register for an account at http://bugsnag.com
2. Download the Bugsnag client (https://github.com/bugsnag/bugsnag-php/archive/v1.0.6.zip)
   and extract the file under sites/all/libraries.
3. Download and enable this module.


Configuration
-------------

1. Edit your settings.php file and add the following:

    // Bugsnag
    require_once("sites/all/libraries/bugsnag/lib/bugsnag.php");
    Bugsnag::register('YOUR_API_KEY');
    set_error_handler("Bugsnag::errorHandler");
    set_exception_handler("Bugsnag::exceptionHandler");
2. For development environments, also add the following to settings.php:

    Bugsnag::setReleaseStage("development");

3. Configure at Administer > Configuration > Development > Bugsnag (requires
   administer site configuration permission).
4. Enter API Key (this might be redundant)


Maintainers
-----------

Shawn Price (langworthy): http://drupal.org/user/25556
