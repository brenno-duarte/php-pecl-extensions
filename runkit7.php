<?
require_once 'vendor/autoload.php';

/**
 * Convert a base class to an inherited class, add ancestral methods when
 * appropriate
 *
 * @param string $classname  Name of class to be adopted
 * @param string $parentname Parent class which child class is extending
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_class_adopt($classname, $parentname)
{
}

/**
 * Convert an inherited class to a base class, removes any method whose scope is
 * ancestral
 *
 * @param string $classname Name of class to emancipate
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_class_emancipate($classname)
{
}

/**
 * Similar to define(), but allows defining in class definitions as well
 *
 * @param string $constname Name of constant to declare. Either a string to
 *                          indicate a global constant, or classname::constname
 *                          to indicate a class constant.
 * @param mixed  $value     NULL, Bool, Long, Double, String, or Resource value
 *                          to store in the new constant.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_constant_add($constname, $value)
{
}

/**
 * Redefine an already defined constant
 *
 * @param string $constname Constant to redefine. Either string indicating
 *                          global constant, or classname::constname indicating
 *                          class constant.
 * @param mixed  $newvalue  New value to assign to constant.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_constant_redefine($constname, $newvalue)
{
}

/**
 * Remove/Delete an already defined constant
 *
 * @param string $constname Name of constant to remove. Either a string
 *                          indicating a global constant, or classname::constname
 *                          indicating a class constant.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_constant_remove($constname)
{
}

/**
 * Add a new function, similar to create_function()
 *
 * @param string $funcname Name of function to be created
 * @param string $arglist  Comma separated argument list
 * @param string $code     Code making up the function
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_add($funcname, $arglist, $code)
{
}

/**
 * Copy a function to a new function name
 *
 * @param string $funcname   Name of existing function
 * @param string $targetname Name of new function to copy definition to
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_copy($funcname, $targetname)
{
}

/**
 * Replace a function definition with a new implementation
 *
 * @param string $funcname Name of function to redefine
 * @param string $arglist  New list of arguments to be accepted by function
 * @param string $code     New code implementation
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_redefine($funcname, $arglist, $code)
{
}

/**
 * Remove a function definition
 *
 * @param string $funcname Name of function to be deleted
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_remove($funcname)
{
}

/**
 * Change a function's name
 *
 * @param string $funcname Current function name
 * @param string $newname  New function name
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_rename($funcname, $newname)
{
}

/**
 * Process a PHP file importing function and class definitions, overwriting
 * where appropriate
 *
 * @param string  $filename Filename to import function and class definitions
 *                          from
 * @param integer $flags    Bitwise OR of the RUNKIT_IMPORT_* family of
 *                          constants.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_import($filename, $flags = RUNKIT_IMPORT_CLASS_METHODS)
{
}

/**
 * Check the PHP syntax of the specified file
 *
 * The runkit_lint_file() function performs a syntax (lint) check on the
 * specified filename testing for scripting errors. This is similar to using
 * php -l from the commandline.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * @param string $filename File containing PHP Code to be lint checked
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_lint_file($filename)
{
}

/**
 * Check the PHP syntax of the specified php code
 *
 * The runkit_lint() function performs a syntax (lint) check on the specified
 * php code testing for scripting errors. This is similar to using php -l from
 * the command line except runkit_lint() accepts actual code rather than a
 * filename.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * @param string $code PHP Code to be lint checked
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_lint($code)
{
}

/**
 * Dynamically adds a new method to a given class
 *
 * @param string  $classname  The class to which this method will be added
 * @param string  $methodname The name of the method to add
 * @param string  $args       Comma-delimited list of arguments for the
 *                            newly-created method
 * @param string  $code       The code to be evaluated when methodname is called
 * @param integer $flags      The type of method to create, can be
 *                            RUNKIT_ACC_PUBLIC, RUNKIT_ACC_PROTECTED or
 *                            RUNKIT_ACC_PRIVATE
 *                            <blockquote>
 *                              Note: This parameter is only used as of PHP 5,
 *                              because, prior to this, all methods were public.
 *                            </blockquote>
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_add($classname, $methodname, $args, $code, $flags = RUNKIT_ACC_PUBLIC)
{
}

/**
 * Copies a method from class to another
 *
 * @param string $dClass  Destination class for copied method
 * @param string $dMethod Destination method name
 * @param string $sClass  Source class of the method to copy
 * @param string $sMethod Name of the method to copy from the source class. If
 *                        this parameter is omitted, the value of dMethod is
 *                        assumed.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_copy($dClass, $dMethod, $sClass, $sMethod = null)
{
}

/**
 * Dynamically changes the code of the given method
 *
 * <blockquote>
 *   Note: This function cannot be used to manipulate the currently running (or
 *   chained) method.
 * </blockquote>
 *
 * @param string  $classname  The class in which to redefine the method
 * @param string  $methodname The name of the method to redefine
 * @param string  $args       Comma-delimited list of arguments for the
 *                            redefined method
 * @param string  $code       The new code to be evaluated when methodname is
 *                            called
 * @param integer $flags      The redefined method can be RUNKIT_ACC_PUBLIC,
 *                            RUNKIT_ACC_PROTECTED or RUNKIT_ACC_PRIVATE
 *                            <blockquote>
 *                              Note: This parameter is only used as of PHP 5,
 *                              because, prior to this, all methods were public.
 *                            </blockquote>
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_redefine($classname, $methodname, $args, $code, $flags = RUNKIT_ACC_PUBLIC)
{
}

/**
 * Dynamically removes the given method
 *
 * <blockquote>
 *   Note: This function cannot be used to manipulate the currently running (or
 *   chained) method.
 * </blockquote>
 *
 * @param string $classname  The class in which to remove the method
 * @param string $methodname The name of the method to remove
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_remove($classname, $methodname)
{
}

/**
 * Dynamically changes the name of the given method
 *
 * <blockquote>
 *   Note: This function cannot be used to manipulate the currently running (or
 *   chained) method.
 * </blockquote>
 *
 * @param string $classname  The class in which to rename the method
 * @param string $methodname The name of the method to rename
 * @param string $newname    The new name to give to the renamed method
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_rename($classname, $methodname, $newname)
{
}

/**
 * Determines if the current functions return value will be used
 *
 * <code>
 *  <?php
 *  function foo() {
 *  var_dump(runkit_return_value_used());
 *  }
 *
 *  foo();
 *  $f = foo();
 *  ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   bool(false)
 *   bool(true)
 * </blockquote>
 *
 * @return boolean Returns TRUE if the function's return value is used by the
 *                 calling scope, otherwise FALSE
 */
function runkit_return_value_used()
{
}

/**
 * Specify a function to capture and/or process output from a runkit sandbox
 *
 * Ordinarily, anything output (such as with echo or print) will be output as
 * though it were printed from the parent's scope. Using
 * runkit_sandbox_output_handler() however, output generated by the sandbox
 * (including errors), can be captured by a function outside of the sandbox.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * <blockquote>
 *   Note: Deprecated
 *
 *   As of runkit version 0.5, this function is deprecated and is scheduled to
 *   be removed from the package prior to a 1.0 release. The output handler for
 *   a given Runkit_Sandbox instance may be read/set using the array offset
 *   syntax shown on the Runkit_Sandbox class definition page.
 * </blockquote>
 *
 * <code>
 *  <?php
 *  function capture_output($str) {
 *  $GLOBALS['sandbox_output'] .= $str;
 *
 *  return '';
 *  }
 *
 *  $sandbox_output = '';
 *
 *  $php = new Runkit_Sandbox();
 *  runkit_sandbox_output_handler($php, 'capture_output');
 *  $php->echo("Hello\n");
 *  $php->eval('var_dump("Excuse me");');
 *  $php->die("I lost myself.");
 *  unset($php);
 *
 *  echo "Sandbox Complete\n\n";
 *  echo $sandbox_output;
 *  ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   Sandbox Complete
 *
 *   Hello
 *   string(9) "Excuse me"
 *   I lost myself.
 * </blockquote>
 *
 * @param object $sandbox  Object instance of Runkit_Sandbox class on which to
 *                         set output handling.
 * @param null   $callback Name of a function which expects one parameter.
 *                         Output generated by sandbox will be passed to this
 *                         callback. Anything returned by the callback will be
 *                         displayed normally. If this parameter is not passed
 *                         then output handling will not be changed. If a
 *                         non-truth value is passed, output handling will be
 *                         disabled and will revert to direct display.
 *
 * @return mixed Returns the name of the previously defined output handler
 *               callback, or FALSE if no handler was previously defined.
 */
function runkit_sandbox_output_handler($sandbox, $callback = null)
{
}

/**
 * Return numerically indexed array of registered superglobals
 *
 * @return array Returns a numerically indexed array of the currently registered
 *               superglobals. i.e. _GET, _POST, _REQUEST, _COOKIE, _SESSION,
 *               _SERVER, _ENV, _FILES
 */
function runkit_superglobals()
{
}