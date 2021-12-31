<?php  if ( function_exists("date_default_timezone_set")) date_default_timezone_set ("Etc/GMT+4");
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	Common Functions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/
 */


// ------------------------------------------------------------------------

if ( ! function_exists('is_php'))
{
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param	string
     * @return	bool	TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string) $version;

        if ( ! isset($_is_php[$version]))
        {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}

/**
 * Class registry
 *
 * This function acts as a singleton.  If the requested class does not
 * exist it is instantiated and set to a static variable.  If it has
 * previously been instantiated the variable is returned.
 *
 * @access	public
 * @param	string	the class name being requested
 * @param	string	the directory where the class should be found
 * @param	string	the class name prefix
 * @return	object
 */
if ( ! function_exists('load_class'))
{
	function &load_class($class, $directory = 'libraries', $prefix = 'CI_')
	{
		static $_classes = array();

		// Does the class exist?  If so, we're done...
		if (isset($_classes[$class]))
		{
			return $_classes[$class];
		}

		$name = FALSE;

		// Look for the class first in the local application/libraries folder
		// then in the native system/libraries folder
		foreach (array(APPPATH, BASEPATH) as $path)
		{
			if (file_exists($path.$directory.'/'.$class.'.php'))
			{
				$name = $prefix.$class;

				if (class_exists($name) === FALSE)
				{
					require($path.$directory.'/'.$class.'.php');
				}

				break;
			}
		}

		// Is the request a class extension?  If so we load it too
		if (file_exists(APPPATH.$directory.'/'.config_item('subclass_prefix').$class.'.php'))
		{
			$name = config_item('subclass_prefix').$class;

			if (class_exists($name) === FALSE)
			{
				require(APPPATH.$directory.'/'.config_item('subclass_prefix').$class.'.php');
			}
		}

		// Did we find the class?
		if ($name === FALSE)
		{
			// Note: We use exit() rather then show_error() in order to avoid a
			// self-referencing loop with the Excptions class
			exit('Unable to locate the specified class: '.$class.'.php');
		}

		// Keep track of what we just loaded
		is_loaded($class);

		$_classes[$class] = new $name();
		return $_classes[$class];
	}
}

// ------------------------------------------------------------------------

/**
* Loads the main config.php file
*
* This function lets us grab the config file even if the Config class
* hasn't been instantiated yet
*
* @access	private
* @return	array
*/
if ( ! function_exists('get_config'))
{
	function &get_config($replace = array())
	{
		static $_config;

		if (isset($_config))
		{
			return $_config[0];
		}

		// Is the config file in the environment folder?
		// APPPATH = /www/huangguan/hg3088/application/
		if (! file_exists($file_path = APPPATH.'config/config.php'))
		{
			///www/huangguan/hg3088/codeIgniter/application/config/config.php
			$file_path = APPPATH.'config/config.php';
		}

		// Fetch the config file
		if ( ! file_exists($file_path))
		{
			exit('The configuration file does not exist.');
		}

		require($file_path);
		//require_once(PROJECT_ROOT."/common/common_function.php");

		// Does the $config array exist in the file?
		if ( ! isset($config) OR ! is_array($config))
		{
			exit('Your config file does not appear to be formatted correctly.');
		}

		// Are any values being dynamically replaced?
		if (count($replace) > 0)
		{
			foreach ($replace as $key => $val)
			{
				if (isset($config[$key]))
				{
					$config[$key] = $val;
				}
			}
		}

		$_config[0] =& $config;
		return $_config[0];
	}
} 

// ------------------------------------------------------------------------

/**
* Returns the specified config item
*
* @access	public
* @return	mixed
*/
if ( ! function_exists('config_item'))
{
	function config_item($item)
	{
		static $_config_item = array();

		if ( ! isset($_config_item[$item]))
		{
			$config =& get_config();

			if ( ! isset($config[$item]))
			{
				return FALSE;
			}
			$_config_item[$item] = $config[$item];
		}

		return $_config_item[$item];
	}
}


// ------------------------------------------------------------------------

/**
* Error Logging Interface
*
* We use this as a simple mechanism to access the logging
* class and send messages to be logged.
*
* @access	public
* @return	void
*/
if ( ! function_exists('log_message'))
{
	function log_message($level = 'error', $message, $php_error = FALSE)
	{
		static $_log;

		if (config_item('log_threshold') == 0)
		{
			return;
		}

		$_log =& load_class('Log');
		$_log->write_log($level, $message, $php_error);
	}
}


// ------------------------------------------------------------------------

/**
* Returns HTML escaped variable
*
* @access	public
* @param	mixed
* @return	mixed
*/
if ( ! function_exists('html_escape'))
{
	function html_escape($var)
	{
		if (is_array($var))
		{
			return array_map('html_escape', $var);
		}
		else
		{
			return htmlspecialchars($var, ENT_QUOTES, config_item('charset'));
		}
	}
}

/* End of file Common.php */
/* Location: ./system/core/Common.php */