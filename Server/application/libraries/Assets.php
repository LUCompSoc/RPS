<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Assets Class
 *
 * The Assets class works with the Template class to provide powerful theme/template functionality.
 *
 * @package		Application
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Alexander Jung
 *
 */
class Assets
{
	/**
	 * Stores the super object.
	 *
	 * @access protected
	 * @var object
	 */
	protected $super;

	/**
	 * Flags
	 *
	 * @access private
	 * @var object
	 */
	private $flags = array
	(
		'styles_compressor_loaded' => false
	,	'scripts_compressor_loaded' => false
	,	'styles_lesscss_loaded' => false
	,	'scripts_closure_loaded' => false
	);

	/**
	 * Third Party objects
	 *
	 * @access protected
	 * @var object
	 */
	protected $_lesscss;

	/**
	 * Third Party objects
	 *
	 * @access protected
	 * @var object
	 */
	protected $_closure;

	/**
	 * Default groups
	 *
	 * @access private
	 * @var object
	 */
	private $default_group = array
	(
		'styles' => '__styles__'
	,	'scripts' => '__scripts__'
	);

	/**
	 * Default groups
	 *
	 * @access private
	 * @var object
	 */
	private $queue = array();

	/**
	 * Outputted group to prevent overlap
	 *
	 * @access private
	 * @var object
	 */
	private $outputted = array();

	/**
	 * Caching method
	 *
	 * <!> UNDER NO CIRCUMSTANCES SHOULD THIS BE CHANGED!
	 *
	 * This is responsible for:
	 * http://example.com/cache/1234567890.type
	 *
	 * @access protected
	 * @var object
	 */
	protected $cache_method	= 'cache_file';

	/**
	 * Directories
	 *
	 * @access	public
	 */
	public $dir = array();

	/**
	 * Paths
	 *
	 * @access	public
	 */
	public $path = array();

	/**
	 * Folders
	 *
	 * @access	public
	 */
	public $url = array();

	/**
	 * LESSCSS Settings
	 *
	 * @access	public
	 */
	public $_cssmin = array
	(
		'plugins' => array()
	,	'filters' => array()
	);

	/**
	 * Default settings
	 *
	 * @access	public
	 */
	protected $defaults = array();

	/**
	 * Config
	 *
	 * @access	public
	 */
	protected $config = array();

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * This if here solely for CI loading to work. Just calls the init( ) method.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->super =& get_instance();
		$this->init();
	}

	//--------------------------------------------------------------------

	/**
	 * Load the assets config file, and inserts the base
	 * css and js into our array for later use. This ensures
	 * that these files will be processed first, in the order
	 * the user is expecting, prior to and later-added files.
	 *
	 * @return void
	 */
	public function init()
	{
		$this->super->config->load('assets');
		$this->super->load->helper('url');
		$this->super->load->helper('string');
		$this->super->load->helper('html');

		// Set up variables
		$this->config = $this->super->config->item('assets');
		$this->defaults = $this->config['defaults'];

		// Initial directories
		foreach($this->config['paths'] as $index => $value)
		{
			$this->dir[$index] = $value;
		}

		// Everything else
		//$this->path = array();
		$this->url = array();

        foreach($this->dir as $index => $value)
		{
			$this->url[$index] = reduce_double_slashes(base_url() . $this->config['public_url_suffix'] . $value . DIRECTORY_SEPARATOR);
			$this->path[$index][] = reduce_double_slashes(APPPATH . $value . DIRECTORY_SEPARATOR);
			
			/*foreach(modules::list_modules(false, true) as $location)
			{
				if(is_dir($location . $value))
				{
					$this->path[$index][] = reduce_double_slashes($location . $value . DIRECTORY_SEPARATOR);
				}
			}*/
		}

        /**/


		/*
		foreach(array(template::theme(true), $this->super->config->item('template.admin_theme'), $this->super->config->item('template.default_theme'), template::theme()) as $theme)
		{
			foreach($this->dir as $index => $value)
			{
				$base_path = reduce_double_slashes(template::$theme_path . '/' . $theme . '/' . $this->dir['assets'] . '/');
				$base_url = reduce_double_slashes(base_url() . '/' . template::$theme_path . '/' . $theme . '/' . $this->dir['assets'] . '/');
				$this->path[$index]	= reduce_double_slashes($base_path . $this->dir[$index] . '/');
				$this->url[$index]	= reduce_double_slashes($base_url . $this->dir[$index]) . '/';
			}
		}

		*/

		log_message('debug', 'Assets library loaded.');
	}

	//--------------------------------------------------------------------

	/**
	 * Load autoload theme contents
	 *
	 * @return void
	 */
	public function autoloader()
	{
		if(defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
		{
			include(APPPATH . 'config/' . ENVIRONMENT . '/autoload.php');
		}
		else
		{
			include(APPPATH.'config/autoload.php');
		}

		if (!isset($autoload) || !isset($autoload['assets']) || !isset($autoload['assets']))
		{
			return false;
		}

		foreach($autoload['assets'] as $asset => $groups)
		{
			foreach($groups as $group => $files)
			{
				$this->assets($asset, $files, $group);
			}
		}

		return true;
	}

	//--------------------------------------------------------------------

	/**
	 * Shortcut add stylesheets to group
	 *
	 * @access	public
	 * @param	mixed	$files		The style(s) to have links rendered for.
	 * @param	string	$group		Group
	 * @param	array	$attributes	Attributes
	 * @return	void
	 */
	public function add_style($files = null, $group = null, $attributes = null, $prepend = false)
	{
		$this->assets('styles', $files, $group, $attributes, $prepend);
	}

	//--------------------------------------------------------------------

	/**
	 * shortcut add scripts to group
	 *
	 * @access	public
	 * @param	mixed	$files		The scripts(s) to have links rendered for.
	 * @param	string	$group		Group
	 * @param	array	$attributes	Attributes
	 * @return	void
	 */
	public function add_script($files = null, $group = null, $attributes = null, $prepend = false)
	{
		$this->assets('scripts', $files, $group, $attributes, $prepend);
	}

	/**
	 *
	 */
	public static function images()
	{
	}

	/**
	 * Adds assets to groups
	 *
	 * @access	public
	 * @param	mixed	$asset		The asset
	 * @param	mixed	$styles		The style(s) to have links rendered for.
	 * @param	string	$group		Group
	 * @param	array	$attributes	Attributes
	 * @return	void
	 */
	public function assets($asset = null, $files = null, $group = null, $attributes = null, $prepend = false)
	{
		$this->super->benchmark->mark("assets::assets()_start");
		
		if(is_null($asset) && is_null($files))
		{
			// Nothing to do here
			$this->super->benchmark->mark("assets::assets()_end");
			return;
		}
		else if (is_null($asset) && !is_null($files))
		{
			/**
			 * Lazy self sorting initiate!
			 */
			$temp					= $reverse = $sentence = array();

			// Make a reverse file type array
			// E.g. array('css' => 'styles', 'js' => 'scripts');
			foreach($this->config['file_types'] as $index => $values)
			{
				foreach($values as $value)
				{
					$reverse[$value] = $index;
				}
			}

			// Find types and sort
			foreach($files as $file)
			{
				$type				= end(explode('.', $file));

				if(in_array($type, $reverse))
				{
					$temp[$reverse[$type]][] = $file;
				}
			}

			// Initiate assets method on grouped asset files
			foreach($temp as $index => $values)
			{
				$this->assets($index, $values, $group, $attributes);
				// Debugging text
				$sentence[]			= sizeof($values) . ' ' . $index . '(s)';
			}

			log_message('debug', 'Lazy asset queuing intitiated for ' . implode(', '. $sentence));
		}

		if(is_string($files))
		{
			$files					= array($files);
		}

		/**
		 * Check if files exist and what not
		 */
		// Files that do exist
		$real						= array();
		$unsorted					= array();
		$unsorted_straight			= array();
		//var_dump('---');
		/*foreach($this->path as $_theme => $paths) {
			foreach($files as $file) {
				if(is_file($paths[$asset] . $file)) {
					//var_dump($asset . '-' . $theme . '-' . $_theme . '-' . $file);
					if($theme !== $_theme && $theme == 'core') {
						$unsorted_straight[$asset][$file][] = $_theme;
					}
				}
			}
		}
		//var_dump($unsorted_straight);
		foreach($unsorted_straight as $_asset => $_files) {
			foreach($_files as $_file => $_themes) {
				$unsorted[end($_themes)][$_asset][] = $_file;
				foreach($files as $index => $file) {
					if($_file == $file) {
						unset($files[$index]);
					}
				}
			}
		}
		var_dump($unsorted_straight);
		if(sizeof($unsorted) > 0) {
			foreach($unsorted as $_theme => $assets) {
				foreach($assets as $_assets => $_files) {
					$this->assets($_assets, $_theme, $_files, $group, $attributes, $prepend);
				}
			}
		}*/
		//var_dump($asset . ' - ' . $theme . ' - ' . $group);
		//$files 						= $real;
		//var_dump($files);
		//var_dump('---');

		if(empty($files))
		{
			return;
		}

		// Check group and assign default if undefined
		if(is_null($group))
		{
			$group		= $this->default_group[$asset];
		}

		if(!is_array($files))
		{
			$temp					= $files;
			$files					= array(array
			(
				'file'				=> $temp
			));
		}
		else
		{
			$temp					= array();
			foreach($files as $file)
			{
				$temp[]				= array
				(
					'file'			=> $file
				);
			}
			$files 					= $temp;
		}

		// Assign defaults if non-existant
		if(!is_array($attributes))
		{
			$attributes				= array();
		}

		foreach($files as $key => $file)
		{
			$files[$key]['attributes'] = $attributes;
			$differences = array_diff(array_keys($this->defaults[$asset]), array_keys($attributes));
			
			foreach($differences as $index)
			{
				$files[$key]['attributes'][$index] = $this->defaults[$asset][$index];
			}
		}

		// Just to make sure
		if(!isset($this->queue[$asset][$group]))
		{
			$this->queue[$asset][$group] = array();
		}

		if($prepend == true)
		{
			$this->queue[$asset][$group] = array_merge($files, $this->queue[$asset][$group]);
		}
		else
		{
			$this->queue[$asset][$group] = array_merge($this->queue[$asset][$group], $files);
		}

		// If core files are added, look for override stuffs
		$this->super->benchmark->mark("assets::assets()_end");
	}

	//--------------------------------------------------------------------

	/**
	 * Outputs data
	 *
	 * @access	public
	 * @param	mixed	$asset		The asset
	 * @param	string	$group		Group
	 * @param	bool	$default	If the group should be the default __{$asset}__ only
	 * @return	void
	 */
	public function output($asset = null, $group = null, $default = false, $just_link = false)
	{
		$this->super->benchmark->mark("assets::output(" . $asset . "/" . $group . ")_start");
		$output = '';
		$files = array();
		$file_names = array();

		if(is_null($asset))
		{
			return;
		}

		// Override $group
		if($default == true)
		{
			$group = $this->default_group[$asset];
		}

		// All files?
		if(is_null($group))
		{
			if(isset($this->queue[$asset]))
			{
				foreach($this->queue[$asset] as $group => $details)
				{
					$output .= $this->output($asset, $group, $default, $just_link);
				}

				return $output;
			}
		}
		else
		{
			if(!isset($this->outputted))
			{
				$this->outputted = array();
			}

			if(!isset($this->outputted[$asset]))
			{
				$this->outputted[$asset] = array();
			}

			if (in_array($group, $this->outputted[$asset]))
			{
				//return;
			}
			else if (isset($this->queue[$asset][$group]))
			{
				$files = $this->queue[$asset][$group];
				$this->outputted[$asset][] = $group;
			}
			else
			{
				return;
			}
		}

		if(empty($files))
		{
			return;
		}

		$file_names = array();

		// Try and find the actual files:
		foreach($files as $index => $value)
		{
			$found = false;
			$file = $value['file'];

			foreach($this->path[$asset] as $location)
			{
				if(file_exists($location . DIRECTORY_SEPARATOR . $file))
				{
					$found = true;
					$files[$index]['path'] = reduce_double_slashes($location . DIRECTORY_SEPARATOR);
				}
			}

			// Uh oh
			if(!isset($files[$index]['path']))
			{
				// Check if a module has been specified
				$path = '';
				$module = $this->super->router->get_module();

				// Is this in a sub-folder? If so, parse out the filename and path.
				if (($last_slash = strrpos($file, '/')) !== FALSE)
				{
					$path = substr($file, 0, ++$last_slash - 1);
					$file = substr($file, $last_slash);
				}

				$_file = modules::file_path($module, $this->dir[$asset] . DIRECTORY_SEPARATOR . $path, $file);


				if(is_null($_file))
				{
					$segments = explode(DIRECTORY_SEPARATOR, $path);
					$module = $segments[0];
					array_shift($segments);
					$path = implode(DIRECTORY_SEPARATOR, $segments);
					$_file = modules::file_path($module, $this->dir[$asset] . DIRECTORY_SEPARATOR . $path, $file);
				}

				if(!is_null($_file))
				{
					$found = true;
					$files[$index]['file'] = basename($_file);
					$files[$index]['path'] = reduce_double_slashes(dirname($_file)) . DIRECTORY_SEPARATOR;
				}
			}

			if($found)
			{
				$file_names[] = $file;
			}
			else
			{
				unset($files[$index]);
			}
		}

		$cache_name = sha1(implode(',', $file_names)) . '.' . current($this->config['file_types'][$asset]);
		$cache_path = reduce_double_slashes($this->config['cache_path'] . DIRECTORY_SEPARATOR . $this->config['paths'][$asset] . DIRECTORY_SEPARATOR . $cache_name);

		// Keep regenerating cache if development mode
		if(ENVIRONMENT == 'development' || !$this->super->cache->file->get($cache_path))
		{
			$output = '';
			$time = time();

			switch($asset)
			{
				case 'styles':
					$finished = array();
					if($this->config['enable_less'] == true)
					{
						$this->_init_lesscss();
						foreach($files as $file)
						{
							//var_dump($this->path[$asset] . $file['file']);
							// Search for less files..
							if(preg_match('/\.less/i', $file['file']))
							{
								$output .= $this->_lesscss->compileFile($file['path'] . $file['file']);
								/*
								$file_names = array();
								foreach($this->_less->imported as $file) {
									$file_names[] = end(explode('/', $file));
								}
								*/
								$finished[$asset][] = $file['file'];
							}
						}
					}

					foreach($files as $file)
					{
						if(!in_array($file['file'], $finished[$asset]))
						{
							$output .= file_get_contents($file['path'] . $file['file']) . "\n";
						}
					}

					if($this->config['minify_styles'] == true) 
					{
						$this->_init_styles_compressor();
						$output = styles_compressor::process($output);
					}

					break;

				case 'scripts':
					foreach($files as $file)
					{
						$output .= file_get_contents($file['path'] . $file['file']) . "\n";
					}

					if($this->config['minify_scripts'] == true)
					{
						$this->_init_scripts_compressor();
						$output = scripts_compressor::process($output);
					}
					if($this->config['minify_scripts_closure'] == true)
					{
						$this->_init_scripts_closure();
						$output	= $this->_closure->addCode($output)
								->simpleMode()
								->write();
					}
					break;
			}

			$output	= "/**\n"
					. " * " . $cache_name . "\n *\n"
					. (ENVIRONMENT !== 'production' ? " * @includes\t" . implode(",\n *\t\t", $file_names) . "\n *\n" : '')
					. " * @version\tv" . $time . "\n"
					. " * @copyright\t(c) " . date('Y') . "\n"
					. " */\n" . $output;

			$this->super->cache->file->save
			(
				$cache_path
			,	$output
			,	0
			,	0775
			);
		}
		else
		{
			$meta = $this->super->cache->file->get_metadata($cache_path);
			$time = $meta['mtime'];
		}

		$link	= prep_url($this->url[$asset] . $cache_name
				. ($this->config['version_date'] == true ? '?v' . $time : ''));

		if ($just_link == true)
		{
			$return = $link;
		}
		else
		{
			switch($asset)
			{
				case 'styles':
					$return = link_tag($link);
					break;
				case 'scripts':
					$return = script_tag($link);
					break;
				default:
					$return = '';
					break;
			}
		}

		$this->super->benchmark->mark("assets::output(" . $asset . "/" . $group . ")_end");

		return $return;
	}

	//--------------------------------------------------------------------

	/**
	 * Initiate LESSCSS
	 *
	 * @static
	 * @access	protected
	 * @return	void
	 */
	protected function _init_lesscss()
	{
		if($this->_is_loaded('styles_lesscss'))
		{
			return true;
		}

		$this->super->benchmark->mark("assets::_init_styles_lesscss()_start");

		// Include library
		include(reduce_double_slashes(APPPATH . '/third_party/assets/styles_lesscss.php'));

		$this->_lesscss = new lessc();
		$this->_lesscss->setVariables($this->config['lesscss_variables']);

		$this->super->benchmark->mark("assets::_init_styles_lesscss()_end");
		$this->flags['styles_lesscss_loaded'] = true;
	}

	//--------------------------------------------------------------------

	/**
	 * Initiate CSSMin
	 *
	 * @static
	 * @access	protected
	 * @return	void
	 */
	protected function _init_styles_compressor()
	{
		if($this->_is_loaded('styles_compressor'))
		{
			return true;
		}

		$this->super->benchmark->mark("assets::_init_styles_compressor()_start");

		// Include library
		include(reduce_double_slashes(APPPATH.'/third_party/assets/styles_compressor.php'));

		$this->super->benchmark->mark("assets::_init_styles_compressor()_end");
		$this->flags['styles_compressor_loaded'] = true;
	}

	//--------------------------------------------------------------------

	/**
	 * Initiate JSMin
	 *
	 * @static
	 * @access	protected
	 * @return	void
	 */
	protected function _init_scripts_compressor()
	{
		if($this->_is_loaded('scripts_compressor'))
		{
			return true;
		}

		$this->super->benchmark->mark("assets::_init_scripts_compressor()_start");

		// Include library
		include(reduce_double_slashes(APPPATH.'/third_party/assets/scripts_compressor.php'));

		$this->super->benchmark->mark("assets::_init_scripts_compressor()_end");
		$this->flags['scripts_compressor_loaded'] = true;
	}

	//--------------------------------------------------------------------

	/**
	 * Initiate JSMin
	 *
	 * @static
	 * @access	protected
	 * @return	void
	 */
	protected function _init_scripts_closure()
	{
		if($this->_is_loaded('scripts_closure'))
		{
			return true;
		}

		$this->super->benchmark->mark("assets::_init_scripts_closure()_start");

		// Include library
		include(reduce_double_slashes(APPPATH.'/third_party/assets/scripts_closure.php'));

		$this->_closure = new scripts_closure();

		$this->super->benchmark->mark("assets::_init_scripts_closure()_end");
		$this->flags['scripts_closure_loaded'] = true;
	}

	//--------------------------------------------------------------------

	/**
	 * Check if extra libraries are loaded
	 *
	 * @static
	 * @access	protected
	 * @return	void
	 */
	protected function _is_loaded($what = null)
	{
		if(is_null($what))
		{
			return undefined;
		}

		if($this->config['freeze'] == true)
		{
			// Returning true prevents anything from loaded.
			// "False truth"
			return true;
		}

		if(!isset($this->flags[$what . '_loaded']))
		{
			return false;
		}
		else if($this->flags[$what . '_loaded'] !== true)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

/* End of file assets.php */
