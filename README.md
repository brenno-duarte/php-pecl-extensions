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
- ds
- HRTime
- inotify
- JSONPath
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

- amqp
- apcu
- ds
- grpc
- igbinary
- imagick
- imap
- memcache/memcached
- mongodb
- pcov
- redis
- solr
- ssh2
- timezonedb
- var_representation
- xdebug
- xhprof
- xxtea
- yac
- yaconf
- yaf
- zstd

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

## Extra files

Some extensions, such as imagick, require the use of several other DLL's. These DLL's will be inside the `ext-files` folder.

Keep in mind that this folder will not be downloaded when you add this component using Composer. You must download the files manually.