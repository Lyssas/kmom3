<!-- Grundstruktur -->
<?php

//
// PHASE: BOOTSTRAP
//
define('RED_INSTALL_PATH', dirname(__FILE__));
define('RED_SITE_PATH', RED_INSTALL_PATH . '/site');

require(RED_INSTALL_PATH.'/src/CRed/bootstrap.php');

$RED = CRed::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$RED->FrontControllerRoute();

//
// PHASE: THEME ENGINE RENDER
//
$RED->ThemeEngineRender();
