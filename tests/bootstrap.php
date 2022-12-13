<?php

/**
 * Boot unit tests
 *
 * We're using PHPUnit and Brain\Monkey as unit test vendors
 */

/**
 * --------------------------------------------------------------------------
 * Include autoload
 * --------------------------------------------------------------------------
 *
 * First we need to load the composer autoloader so we can use unit tests
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * --------------------------------------------------------------------------
 * Include WordPress core mocked functions
 * --------------------------------------------------------------------------
 *
 * Test helpers 
 */
require_once __DIR__ . '/core-functions.php';
