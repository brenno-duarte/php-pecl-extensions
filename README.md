<img src="https://pecl.php.net/img/peclsmall.gif">

A collection of pollyfills and DLL files for those who don't have PECL installed. This package also provides an easier way to install DLL for some PECL packages like memcached and APCu.

## Requeriments

- PHP >= 8.3
- PHP CURL
- PHP OpenSSL
- PHP Hash

## Installing via Composer

```
composer require brenno-duarte/php-pecl-extensions
```

## Using polyfills from PECL extensions

This package has polyfills for the following extensions:

- base58
- HRTime
- inotify
- OAuth
- scrypt
- simdjson
- sodium
- ssdeep
- statistic (partial)
- translit
- uopz (partial)
- var_representation
- xdiff (partial)
- xxtea
- Yaconf
- yaml

The only thing you will need to do is call the `vendor/autoload.php` folder. Consult the documentation for each extension to learn how to use them.

To see the full list of available polyfills and DLLs, use this command:

```bash
vendor/bin/pecl ext:list
```

## Installing PECL extensions

The following DLL files are available in this package:

- apcu
- igbinary
- imagick
- imap
- memcache/memcached
- mongodb
- redis
- solr
- timezonedb
- var_representation
- xdebug
- xhprof
- xxtea
- yac
- yaconf

To install the required DLLs, use the following command:

```bash
vendor/bin/pecl install extension_name
```

Example:

```bash
vendor/bin/pecl install memcached
```

If you want to know if an extension was installed successfully, use the `status` command:

```bash
vendor/bin/pecl status memcached
```

## Running required services

**WARNING: Services is exclusive to those using Windows. We will be working to implement it for other OS as well.**

Some services are required for extensions to work. For example, memcached.

To run a service, use the command:

```bash
vendor/bin/pecl service service-name
```

Example

```bash
vendor/bin/pecl service memcached
```

To stop a running service, use the `--stop` option.

```bash
vendor/bin/pecl service memcached --stop
```

To see available services, use the `--list` option.

```bash
vendor/bin/pecl service --list
```
