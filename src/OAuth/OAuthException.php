<?php

namespace OAuth;

class OAuthException extends \Exception
{
	public $lastResponse;
	public $debugInfo;
}