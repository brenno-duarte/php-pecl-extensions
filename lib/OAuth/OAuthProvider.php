<?php

namespace PeclPolyfill\OAuth;

class OAuthProvider
{
    public function __construct()
    {
        throw new OAuthException('OAuthProvider not implemented. Use "OAuth" class instead');
    }
}
