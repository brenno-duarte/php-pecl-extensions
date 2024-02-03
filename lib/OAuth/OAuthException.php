<?php

namespace PeclPolyfill\OAuth;

class OAuthException extends \Exception
{
	public $lastResponse;
	public $debugInfo;
}