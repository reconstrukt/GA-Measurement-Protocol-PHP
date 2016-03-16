GA Measurement Protocol PHP Client
==================================

Simple client wrapper class for the [GA Measurement Protocol v1](https://developers.google.com/analytics/devguides/collection/protocol/v1/).  Requires curl. 

Features
--------

 - Server-side pageview and event tracking 
 - Associates server-side events to a current session's client ID, by parsing 'clientId' from _ga cookie (if exists)
 - Supports debug mode for GA's hit validation endpoint
 - Does not handle batch payloads, yet
 

Usage
-----

Pretty straightforward, just drop in and go.

    ```php
        <?php
        // drop in the class
        require_once 'lib/simple-ga.php';

        // init with your GA Site ID
        $gamp = new gamp( 'UA-XXXXXXXX-X' );

        // set pageview
        (bool) $ok = $gamp->pageview( '/url/to/track', 'My Page Title' );

        // set event
        (bool) $ok = $gamp->event( 'Test', 'GAMP-Event-Hit' );
    ```

Also supports a 'debug' option, based on [GA's hit validation docs](https://developers.google.com/analytics/devguides/collection/protocol/v1/validating-hits).  Usage:

Usage:

    ```php
        <?php
        require_once 'lib/simple-ga.php';
        $gamp = new gamp( 'UA-XXXXXXXX-X' );
        
        // I can haz debug
        $gamp->debug = true;

        // set pageview + event
        $ok = $gamp->pageview();
        $ok = $gamp->event( 'Test', 'GAMP-Event-Hit' );
        
        // wassup 
        print_r( $gamp->log );
    ```

