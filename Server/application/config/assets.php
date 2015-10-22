<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 
 */
	$config['assets']['cache_path']						= 'assets/';
	$config['assets']['public_url_suffix']				= 'public/';

/*
 *--------------------------------------------------------------------------
 * Processing assets
 *--------------------------------------------------------------------------
 *
 * Flags for processing actions
 *
 */
	$config['assets']['minify_styles']					= true;
	$config['assets']['minify_scripts']					= false;
	$config['assets']['minify_scripts_closure']			= false;
	$config['assets']['enable_less']					= true;
	$config['assets']['freeze']							= false;
	$config['assets']['version_date']					= true;
/*
 *--------------------------------------------------------------------------
 * Default paths and directories
 *--------------------------------------------------------------------------
 * This is used for ALL THEMES including the CORE.
 *
 */
//	$config['assets']['paths']['assets']				= '';
	$config['assets']['paths']['styles']				= 'styles';
	$config['assets']['paths']['scripts']				= 'scripts';
	$config['assets']['paths']['images']				= 'images';
	$config['assets']['paths']['cache']					= 'cache';
/*
 *--------------------------------------------------------------------------
 * Acceptable file extensions for assets
 *--------------------------------------------------------------------------
 *
 * Notes: The first item will be file type of preference in cached files.
 *
 */
	$config['assets']['file_types']['styles']			= array(
		'css', 'less'
	);
	$config['assets']['file_types']['scripts']			= array(
		'js'
	);
	$config['assets']['file_types']['images']			= array(
		'png', 'jpg', 'jpeg', 'gif'
	);
/*
 *--------------------------------------------------------------------------
 * Cache
 *--------------------------------------------------------------------------
 *
 * Define if the cache folder should be cleared when generating new cache files
 *
 */
	$config['assets']['auto_clear_cache']				= true;
	$config['assets']['auto_clear_style_cache']			= false;
	$config['assets']['auto_clear_script_cache']		= false;
/*
 *--------------------------------------------------------------------------
 * Default values
 *--------------------------------------------------------------------------
 *
 */
	$config['assets']['defaults']['styles']['rel']		= 'stylesheet';
	$config['assets']['defaults']['styles']['type']		= 'text/css';
	$config['assets']['defaults']['styles']['media']	= 'screen';

	$config['assets']['defaults']['scripts']['type']	= 'text/javascript';
/*
 *--------------------------------------------------------------------------
 *
 *--------------------------------------------------------------------------
 *
 * Define if the cache folder should be cleared when generating new cache files
 *
 */

	$config['assets']['lesscss_variables']		= array(
		"base_url"								=> "'" . get_instance()->config->base_url() . "'"
	,	"assets_url"							=> "'" . get_instance()->config->base_url($config['assets']['public_url_suffix']) . "'"
	);

/*

 *--------------------------------------------------------------------------
 * Processing assets
 *--------------------------------------------------------------------------
 *
 * Flags for processing actions
 *
 *
	$config['assets']['minify_css']				= true;
	$config['assets']['minify_js']				= true;
	$config['assets']['enable_less']			= true;
	$config['assets']['enable_coffeescript']	= true;
	$config['assets']['freeze']					= false;
/*
 *--------------------------------------------------------------------------
 * Cache
 *--------------------------------------------------------------------------
 *
 * Define if the cache folder should be cleared when generating new cache files
 *
 *
	$config['assets']['auto_clear_cache']		= true;
	$config['assets']['auto_clear_css_cache']	= false;
	$config['assets']['auto_clear_js_cache']	= false;
/*
 *--------------------------------------------------------------------------
 * Default paths and directories, tags
 *--------------------------------------------------------------------------
 *
 * Leave the base_url at null on default
 * Default directories containing the assets
 * Option to use HTML5 tags
 *
 *
	$config['assets']['base_url']				= null;
	$config['assets']['assets_dir']				= '';
	$config['assets']['js_dir']					= 'scripts';
	$config['assets']['css_dir']				= 'styles';
	$config['assets']['cache_dir']				= 'cache';
	$config['assets']['img_dir']				= 'images';
	$config['assets']['html5']					= true;
/*
 *--------------------------------------------------------------------------
 * CDN assets
 *--------------------------------------------------------------------------
 *
	$config['assets.cdn']['jquery']				= array(
		'default_version'						=> '1.7.2',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/jquery/%version%/jquery.min.js',
	);
	$config['assets.cdn']['jqueryui']			= array(
		'default_version'						=> '1.8.18',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/jqueryui/%version%/jquery-ui.min.js',
	);
	$config['assets.cdn']['jquery-validate']	= array(
		'default_version'						=> '1.9',
		'path'									=> 'http://ajax.aspnetcdn.com/ajax/jquery.validate/%version%/jquery.validate.min.js',
	);
	$config['assets.cdn']['jquery-mobile']		= array(
		'default_version'						=> '1.0.1',
		'path'									=> 'http://ajax.aspnetcdn.com/ajax/jquery.mobile/%version%/jquery.mobile-%version%.min.js',
	);
	$config['assets.cdn']['jquery-mobile-css']	= array(
		'default_version'						=> '1.0.1',
		'path'									=> 'http://ajax.aspnetcdn.com/ajax/jquery.mobile/%version%/jquery.mobile-%version%.min.css',
	);
	$config['assets.cdn']['jquery-cycle']		= array(
		'default_version'						=> '2.99',
		'path'									=> 'http://ajax.aspnetcdn.com/ajax/jquery.cycle/%version%/jquery.cycle.all.min.js',
	);
	$config['assets.cdn']['chrome-frame']		= array(
		'default_version'						=> '1.0.2',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/chrome-frame/%version%/CFInstall.min.js',
	);
	$config['assets.cdn']['ext-core']			= array(
		'default_version'						=> '3.1.0',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/ext-core/%version%/ext-core.js',
	);
	$config['assets.cdn']['dojo']				= array(
		'default_version'						=> '1.7.2',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/dojo/%version%/dojo/dojo.js',
	);
	$config['assets.cdn']['mootools']			= array(
		'default_version'						=> '1.4.5',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/mootools/%version%/mootools-yui-compressed.js',
	);
	$config['assets.cdn']['prototype']			= array(
		'default_version'						=> '1.7.0.0',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/prototype/%version%/prototype.js',
	);
	$config['assets.cdn']['scriptaculous']		= array(
		'default_version'						=> '1.9.0',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/scriptaculous/%version%/scriptaculous.js',
	);
	$config['assets.cdn']['swfobject']			= array(
		'default_version'						=> '2.2',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/swfobject/%version%/swfobject.js',
	);
	$config['assets.cdn']['webfont']			= array(
		'default_version'						=> '1.0.26',
		'path'									=> 'https://ajax.googleapis.com/ajax/libs/webfont/%version%/webfont.js',
	);

	$config['assets.cssmin.filters']			= array(
		"ImportImports"							=> true,
		"RemoveComments"						=> true,
		"RemoveEmptyRulesets"					=> true,
		"RemoveEmptyAtBlocks"					=> true,
		"ConvertLevel3AtKeyframes"				=> false,
		"ConvertLevel3Properties"				=> false,
		"Variables"								=> true,
		"RemoveLastDelarationSemiColon"			=> true,
	);

	$config['assets.cssmin.plugins']			= array(
		"Variables"								=> true,
		"ConvertFontWeight"						=> false,
		"ConvertHslColors"						=> false,
		"ConvertRgbColors"						=> false,
		"ConvertNamedColors"					=> false,
		"CompressColorValues"					=> false,
		"CompressUnitValues"					=> false,
		"CompressExpressionValues"				=> false
	);
*/
