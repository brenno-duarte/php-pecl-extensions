<?php

/**
 * runkit_import() flag indicating that normal functions should be imported
 * from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_FUNCTIONS', 1);

/**
 * runkit_import() flag indicating that class methods should be imported
 * from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_METHODS', 2);

/**
 * runkit_import() flag indicating that class constants should be imported
 * from the specified file. Note that this flag is only meaningful in PHP
 * versions 5.1.0 and above.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_CONSTS', 4);

/**
 * runkit_import() flag indicating that class standard properties should be
 * imported from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_PROPS', 8);

/**
 * runkit_import() flag indicating that class static properties should be
 * imported from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_STATIC_PROPS', 10);

/**
 * runkit_import() flag representing a bitwise OR of the RUNKIT_IMPORT_CLASS_*
 * constants.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASSES', (
    RUNKIT_IMPORT_CLASS_METHODS
    | RUNKIT_IMPORT_CLASS_CONSTS
    | RUNKIT_IMPORT_CLASS_PROPS
    | RUNKIT_IMPORT_CLASS_STATIC_PROPS
));

/**
 * runkit_import() flag indicating that if any of the imported functions,
 * methods, constants, or properties already exist, they should be replaced
 * with the new definitions. If this flag is not set, then any imported
 * definitions which already exist will be discarded.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_OVERRIDE', 20);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_PUBLIC', 256);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_PROTECTED', 512);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_PRIVATE', 1024);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_STATIC', 1);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_RETURN_REFERENCE', 0x4000000);

/**
 * PHP 5 specific flag to classkit_method_add() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_ACC_PUBLIC', 256);

/**
 * PHP 5 specific flag to classkit_method_add() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_ACC_PROTECTED', 512);

/**
 * PHP 5 specific flag to classkit_method_add() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_ACC_PRIVATE', 1024);

/**
 * PHP 5 specific flag to classkit_import() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_AGGREGATE_OVERRIDE', 32);

/**
 * Defined to the current version of the runkit package.
 *
 * @var integer
 */
define('RUNKIT_VERSION', '1.0.4-dev');

/**
 * Defined to the current version of the runkit package. Only defined when
 * classkit compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_VERSION', '1.0.4-dev');

/**
 * VAR REPRESENTATION
 */

if (!defined('VAR_REPRESENTATION_SINGLE_LINE')) {
    define('VAR_REPRESENTATION_SINGLE_LINE', 1);
}
if (!defined('VAR_REPRESENTATION_UNESCAPED')) {
    define('VAR_REPRESENTATION_UNESCAPED', 2);
}

/**
 * YAC COMPONENT
 */

if (!defined('YAC_VERSION')) {
    define('YAC_VERSION', '2.3.1');
}

if (!defined('YAC_MAX_KEY_LEN')) {
    define('YAC_MAX_KEY_LEN', 48);
}

if (!defined('YAC_MAX_VALUE_RAW_LEN')) {
    define('YAC_MAX_VALUE_RAW_LEN', 67108863);
}

if (!defined('YAC_MAX_RAW_COMPRESSED_LEN')) {
    define('YAC_MAX_RAW_COMPRESSED_LEN', 1048576);
}

if (!defined('YAC_SERIALIZER')) {
    define('YAC_SERIALIZER', 'PHP');
}
