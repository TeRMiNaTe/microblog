<?php

namespace App\Exceptions;

/**
 * Generic Exception for front-facing errors that users should be aware of
 * Users will be redirected back to the referred with an error message
 *
 * @see \App\Middleware\RedirectExceptionListener - Listens for the exception and handles the redirection and message generation
 * @see \App\Middleware\RedirectExceptionHandler - Handles the excetpion message data
 */
class PublicRedirectException extends \Exception
{
}
