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

To see the full list of available polyfills and DLLs, use this command:

```bash
vendor/bin/pecl ext:list
```

## Installing PECL extensions

The DLLs are available in the repository [https://github.com/brenno-duarte/php-pecl](https://github.com/brenno-duarte/php-pecl). You can access the previous link and enter the `extensions/` folder to see the available extensions.

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

# License

MIT