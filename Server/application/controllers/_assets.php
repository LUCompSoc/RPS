<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simple static file server
 * 
 * @package    Application
 * @subpackage Controllers
 * @category   Helpers
 * @author     Alexander Jung
 *
 */
class _assets extends CI_Controller
{
	/**
	 * Simply redirects all calls to the index() method.
	 *
	 * @param string $file The name of the image to return.
	 */
	public function _remap()
	{
		$this->index();
	}

	/**
	 * Performs the image processing based on the parameters provided in the GET request
	 *
	 * @param string $file The name of the image to return.
	 */
	public function index()
	{
		// Quick before it's too late
		$this->output->enable_profiler(false);

		// routing doesn't work (?)

		$config = config_item('assets');
		$request = $config['cache_path'] . str_replace(get_class($this) . '/', '', $this->uri->ruri_string());

		$this->load->helper('file');

		$output = false;

		if (empty($request) || !is_string($request))
		{
			// Kill now!
			show_404();
		}

		/*if($_output = $this->cache->file->get($request))
		{
			$output = $_output;
		}*/

		if($_output = read_file(APPPATH . $request))
		{
			$output = $_output;
		}

		if($output)
		{
			$this->output
			->set_content_type(get_mime_by_extension($request))
			->set_output(trim($output));
		}
		else
		{
			show_404();
		}
	}
}