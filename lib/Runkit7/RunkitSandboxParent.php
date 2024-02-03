<?php

namespace PeclPolyfill\Runkit7;

/**
 * Instantiating the Runkit_Sandbox_Parent class from within a sandbox
 * environment created from the Runkit_Sandbox class provides some (controlled)
 * means for a sandbox child to access its parent.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * In order for any of the Runkit_Sandbox_Parent features to function. Support
 * must be enabled on a per-sandbox basis by enabling the parent_access flag
 * from the parent's context.
 *
 * <blockquote>
 *   <?php
 *   $sandbox = new Runkit_Sandbox();
 *   $sandbox['parent_access'] = true;
 *   ?>
 * </blockquote>
 *
 * <h2>Accessing the Parent's Variables</h2>
 *
 * Just as with sandbox variable access, a sandbox parent's variables may be
 * read from and written to as properties of the Runkit_Sandbox_Parent class.
 * Read access to parental variables may be enabled with the parent_read setting
 * (in addition to the base parent_access setting). Write access, in turn, is
 * enabled through the parent_write setting.
 *
 * Unlike sandbox child variable access, the variable scope is not limited to
 * globals only. By setting the parent_scope setting to an appropriate integer
 * value, other scopes in the active call stack may be inspected instead. A
 * value of 0 (Default) will direct variable access at the global scope. 1 will
 * point variable access at whatever variable scope was active at the time the
 * current block of sandbox code was executed. Higher values progress back
 * through the functions that called the functions that led to the sandbox
 * executing code that tried to access its own parent's variables.
 *
 * <code>
 *   <?php
 *   $php = new Runkit_Sandbox();
 *   $php['parent_access'] = true;
 *   $php['parent_read'] = true;
 *
 *   $test = "Global";
 *
 *   $php->eval('$PARENT = new Runkit_Sandbox_Parent;');
 *
 *   $php['parent_scope'] = 0;
 *   one();
 *
 *   $php['parent_scope'] = 1;
 *   one();
 *
 *   $php['parent_scope'] = 2;
 *   one();
 *
 *   $php['parent_scope'] = 3;
 *   one();
 *
 *   $php['parent_scope'] = 4;
 *   one();
 *
 *   $php['parent_scope'] = 5;
 *   one();
 *
 *   function one() {
 *   $test = "one()";
 *   two();
 *   }
 *
 *   function two() {
 *   $test = "two()";
 *   three();
 *   }
 *
 *   function three() {
 *   $test = "three()";
 *   $GLOBALS['php']->eval('var_dump($PARENT->test);');
 *   }
 *   ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   string(6) "Global"
 *   string(7) "three()"
 *   string(5) "two()"
 *   string(5) "one()"
 *   string(6) "Global"
 *   string(6) "Global"
 * </blockquote>
 *
 * <h2>Calling the Parent's Functions</h2>
 *
 * Just as with sandbox access, a sandbox may access its parents functions
 * providing that the proper settings have been enabled. Enabling parent_call
 * will allow the sandbox to call all functions available to the parent scope.
 * Language constructs are each controlled by their own setting: print and echo
 * are enabled with parent_echo. die() and exit() are enabled with parent_die.
 * eval() is enabled with parent_eval while include, include_once, require, and
 * require_once are enabled through parent_include.
 */
class RunkitSandboxParent
{
    public function __construct()
    {
    }
}