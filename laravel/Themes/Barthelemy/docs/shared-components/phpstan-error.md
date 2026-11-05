---
title: "phpstan error 1"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "phpstan error 1"
issues: []
discussions: []
status: draft
---

# phpstan_error

<!-- Contenuto migrato da _docs/phpstan_error.txt -->

modulo Geo

  Line   \Actions\GetLatitudeLongitudeAction.php
 ------ ------------------------------------------------------
  34     Call to an undefined method
         Illuminate\Database\Eloquent\Model::getAddress().


modulo LU
  Line   Http\Controllers\Auth\LoginController.php
 ------ -----------------------------------------------------------------------
  145    Result of method
         Modules\User\Http\Controllers\Auth\LoginController::sendLockoutResponse
         () (void) is used.

modulo Xot
  Line   Jobs\PanelCrud\IndexStoreAttachJob.php
 ------ -----------------------------------------------------------------------
  28     Method Illuminate\Support\Collection<int,mixed>::get() invoked with 0
         parameters, 1-2 required.

