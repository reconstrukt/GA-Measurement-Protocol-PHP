GA Measurement Protocol PHP Client
==================================

Simple client wrapper class for the [GA Measurement Protocol v1](https://developers.google.com/analytics/devguides/collection/protocol/v1/).  Requires curl. 

Features
--------

 - Server-side pageview and event tracking with GA 
 - Associates server-side events to a current GA session's client ID, by parsing 'clientId' from _ga cookie (if exists)
 - Supports debug mode for GA's hit validation endpoint
 - Does not handle batch payloads, yet
 

Usage
-----

Pretty straightforward, just drop in and go.

```php
<?php
// drop in the class
require_once 'lib/gamp.php';

// init with your GA Site ID
$gamp = new gamp( 'UA-XXXXXXXX-X' );

// set pageview
(bool) $hit = $gamp->pageview( '/url/to/track', 'My Page Title' );

// set event
(bool) $hit = $gamp->event( 'Test', 'GAMP-Event-Hit' );
```

Also supports a 'debug' option, based on [GA's hit validation docs](https://developers.google.com/analytics/devguides/collection/protocol/v1/validating-hits).  Usage:

Usage:

```php
<?php
require_once 'lib/gamp.php';
$gamp = new gamp( 'UA-XXXXXXXX-X' );

// I can haz debug
$gamp->debug = true;

// set pageview + event
$hit = $gamp->pageview();
$hit = $gamp->event( 'Test', 'GAMP-Event-Hit' );

// wassup 
print_r( $gamp->log );
```

