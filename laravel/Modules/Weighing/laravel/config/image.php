<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    // gd: Imagick non e' installato (php -m); con imagick ogni lettura di immagine fallisce. Si cambia solo dopo averlo installato.
    'driver' => 'gd',

];
