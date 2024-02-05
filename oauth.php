<?php

if (!function_exists('oauth_get_sbs')) {
    function oauth_get_sbs($http_method, $uri, $request_parameters = null)
    {
        $request_parameters = ($request_parameters === null ? array() : $request_parameters);
        if (!is_array($request_parameters)) {
            trigger_error('oauth_get_sbs() expects parameter 3 to be array, ' . gettype($request_parameters) . ' given', E_USER_WARNING);
            return null;
        }

        $urlParts = parse_url($uri);
        $scheme = strtolower($urlParts['scheme']);
        $host = strtolower($urlParts['host']);
        $port = empty($urlParts['port']) ? '' : $urlParts['port'];
        if ($port) {
            $port = ($scheme == 'http' && $port == 80) || ($scheme == 'https' && $port == 443) ? '' : ':' . $port;
        }
        $path = empty($urlParts['path']) ? '/' : $urlParts['path'];

        $uriBase = $scheme . '://' . $host . $port . $path;

        parse_str(parse_url($uri, PHP_URL_QUERY), $query_params);
        $params = $query_params + $request_parameters;

        $params = array_diff_key($params, array('oauth_signature' => 1));

        $normalizedParams = array();
        foreach ($params as $key => $value) {
            $normalizedParams[oauth_urlencode($key)] = oauth_urlencode($value);
        }
        uksort($normalizedParams, 'strnatcmp');
        $paramParts = array();
        foreach ($normalizedParams as $key => $value) {
            $paramParts[] = $key . '=' . $value;
        }
        $param_str = implode('&', $paramParts);

        return $http_method . '&' . oauth_urlencode($uriBase) . '&' . oauth_urlencode($param_str);
    }

    function oauth_urlencode($uri)
    {
        return rawurlencode($uri);
    }
}
