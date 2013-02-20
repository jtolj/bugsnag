Bugsnag
=======

Integrate with http://bugsnag.com to compliment watchdog.

Report PHP errors, exceptions, and watchdog messages to Bugsnag.


Installation
------------

1. Register for an account at http://bugsnag.com.
2. Download the Bugsnag client (https://github.com/bugsnag/bugsnag-php/archive/v1.0.6.zip)
   and extract the file under sites/all/libraries.
3. Download and enable this module.


Configuration
-------------

1. Edit your settings.php file and add the following:

    // Bugsnag
    $bugsnag = "sites/all/libraries/bugsnag/lib/bugsnag.php";
    if (file_exists($bugsnag)) {
      require_once("sites/all/libraries/bugsnag/lib/bugsnag.php");
      Bugsnag::register('YOUR_API_KEY');
      set_error_handler("Bugsnag::errorHandler");
      set_exception_handler("Bugsnag::exceptionHandler");
    }
2. For development environments, also add the following within the `if(){ ... }`:

    Bugsnag::setReleaseStage("development");

3. Configure at Administer > Configuration > Development > Bugsnag (requires
   administer site configuration permission).
4. Choose minimum severity threshhold for watchdog reporting.


Maintainers
-----------

Shawn Price (langworthy): http://drupal.org/user/25556
