<img src="https://pecl.php.net/img/peclsmall.gif">

A collection of pollyfills and DLL files for Windows for those who don't have PECL installed. This package also provides an easier way to install DLL for some PECL packages like memcached and APCu.

## Requeriments

- PHP >= 8.3
- Curl extension
- OpenSSL extension
- Hash extension
- GMP extension

## Installing via Composer

```
composer require brenno-duarte/php-pecl-extensions
```

## Using polyfills from PECL extensions

This package has polyfills for the following extensions:

- base58
- ds
- HRTime (partial)
- inotify
- OAuth
- scrypt
- simdjson
- sodium
- ssdeep
- statistic (partial)
- uopz (partial)
- var_representation
- xdiff (partial)
- xxtea
- Yaconf
- yaml (partial)

The only thing you will need to do is call the `vendor/autoload.php` folder. Consult the documentation for each extension to learn how to use them.

# License

MIT