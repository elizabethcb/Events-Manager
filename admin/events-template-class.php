<?php defined('DBEM_DOCROOT_DOCROOT') or exit('No direct script access.');
/**
 * Events Template
 *
 * Originally in ThumbsUp
 * @author     Geert De Deckere <http://www.geertdedeckere.be/>
 * @copyright  (c) 2009 Geert De Deckere
 */

class Events_Template {

	/**
	 * @var  string  template file
	 */
	protected $file;

	/**
	 * @var  array  data to pass on to the template
	 */
	public $data;

	/**
	 * Creates a new template object. This method is chainable.
	 *
	 * @param   string  template file
	 * @return  object  Events_Template
	 */
	public function factory($file = NULL)
	{
		return new Events_Template($file);
	}

	/**
	 * Constructor. Creates a new template object.
	 *
	 * @param   string  template file
	 * @return  void
	 */
	public function __construct($file = NULL)
	{
		$this->file = (string) $file;
	}

	/**
	 * Sets the template file. Overwrites the template file from the constructor.
	 * This method is chainable.
	 *
	 * @param   string  template file
	 * @return  object  Events_Template
	 */
	public function set_file($file)
	{
		$this->file = (string) $file;

		// Chainable method
		return $this;
	}

	/**
	 * Sets template data. This method is chainable.
	 *
	 * @param   mixed   key string or an associative array for multiple variables at once
	 * @param   mixed   value
	 * @return  object  Events_Template
	 */
	public function set($key, $value = NULL) {
		// Set multiple template variables at once
		if (is_array($key)) {
			foreach ($key as $key2 => $value) {
				$this->set($key2, $value);
			}

			// Don't continue further if $key is an array
			return $this;
		}

		// Render nested templates first
		if ($value instanceof Events_Template) {
			$value = $value->render(TRUE);
		}

		// Update the data array
		$this->data[$key] = $value;

		// Chainable method
		return $this;
	}

	/**
	 * Sets template data.
	 *
	 * @param   string  key
	 * @param   mixed   value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * Unsets template data (as of PHP 5.1.0).
	 *
	 * @param   string  key
	 * @return  void
	 */
	public function __unset($key)
	{
		unset($this->data[$key]);
	}

	/**
	 * Checks whether certain template data is set (as of PHP 5.1.0).
	 *
	 * @param   string   template data key
	 * @return  boolean
	 */
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}

	/**
	 * Gets template data.
	 *
	 * @param   string  template data key
	 * @return  mixed   template data; NULL if not found
	 */
	public function __get($key)
	{
		return (isset($this->data[$key])) ? $this->data[$key] : NULL;
	}

	/**
	 * Renders a template, and outputs it (by default).
	 *
	 * @param   boolean  TRUE to return the output as a string instead of echoing it
	 * @return  mixed    void by default; string if $return has been set to TRUE
	 */
	public function render($return = FALSE)
	{
		// Start output buffering
		ob_start();

		// Pass on the data to the template
		extract((array) $this->data);

		// Load and parse the template
		include $this->file;

		// End output buffering
		$output = ob_get_contents();
		ob_end_clean();

		// Return the output as a string
		if ($return === TRUE)
			return $output;

		// Print it
		echo $output;
	}
	


	/**
	 * Renders the template.
	 *
	 * @return  string  template output
	 */
	public function __toString()
	{
		return $this->render(TRUE);
	}

}
