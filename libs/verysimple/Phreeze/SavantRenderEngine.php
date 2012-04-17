<?php
/** @package    verysimple::Phreeze */

require_once("IRenderEngine.php");
require_once('savant/Savant3.php');

/**
 * Implementation of IRenderEngine that uses Savant as the template language
 *
 * @package    verysimple::Phreeze
 * @author     VerySimple Inc.
 * @copyright  1997-2010 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
class SavantRenderEngine implements IRenderEngine
{

	static $TEMPLATE_EXTENSION = ".tpl.php";

	public $savant;


	/**
	 * @param string $templatePath
	 * @param string $compilePath (not used for this render engine)
	 */
	function __construct($templatePath = '',$compilePath = '')
	{
		$this->savant = new Savant3(array('exceptions'=>true));

		// normalize the path
		if (substr($templatePath,-1) != '/' && substr($templatePath,-1) != '\\') $templatePath .= "/";

		if ($templatePath) $this->savant->setPath('template',$templatePath);
	}

	/**
	 * @inheritdoc
	 */
	public function assign($key,$value)
	{
		$this->savant->$key = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function display($template)
	{
		// strip off .tpl from the end for backwards compatibility with older apps
		if (substr($template, -4) == '.tpl') $template = substr($template,0,-4);

		// these two are special templates used by the Phreeze controller and dispatcher
		if ($template == "_redirect")
		{
			header("Location: " . $this->savant->url);
			die();
		}
		elseif ($template == "_error")
		{
			$this->savant->display('_error' . $TEMPLATE_EXTENSION);
		}
		else
		{
			$this->savant->display($template . self::$TEMPLATE_EXTENSION);
		}
	}

	/**
	 * Returns the specified model value
	 */
	public function get($key)
	{
		return $this->savant->$key;
	}

	/**
	 * @inheritdoc
	 */
	public function fetch($template)
	{
		return $this->savant->fetch($template . self::$TEMPLATE_EXTENSION);
	}

}

?>