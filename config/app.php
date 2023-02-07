<?php

/**
 * Application configuration
 *
 * Environment configuration takes priority
 */

return array_merge_recursive([
	// Global app configuration:
	// 'displayErrorDetails' => true,
], $env['app']);
