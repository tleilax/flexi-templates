<?php
namespace Flexi;

/**
 * Abstract template class representing the presentation layer of an action.
 * Output can be customized by supplying attributes, which a template can
 * manipulate and display.
 *
 * @author    Marcus Lunzenauer <mlunzena@uos.de>
 * @author    Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @copyright 2008-2015 Marcus Lunzenauer
 * @license   MIT license http://opensource.org/licenses/MIT
 * @package   Flexi
 * @version   0.6.0
 */

abstract class Template
{
    protected $_attributes;
    protected $_factory;
    protected $_options;
    protected $_layout;
    protected $_template;

    /**
     * Constructor.
     *
     * @param string                 the path of the template.
     * @param Flexi_TemplateFactory  the factory creating this template
     * @param array                  optional array of options
     */
    public function __construct($template, TemplateFactory &$factory, $options = array())
    {
        $this->_template = $template;
        $this->_factory  = $factory;
        $this->_options  = $options;

        $this->clear_attributes();

        $this->set_layout(null);
    }

    /**
     * __set() is a magic method run when writing data to inaccessible members.
     * In this class it is used to set attributes for the template in a
     * comfortable way.
     *
     * @see http://php.net/__set
     *
     * @param    string         the name of the member field
     * @param    mixed          the value for the member field
     */
    public function __set($name, $value)
    {
        $this->set_attribute($name, $value);
    }

    /**
     * __get() is a magic method utilized for reading data from inaccessible
     * members.
     * In this class it is used to get attributes for the template in a
     * comfortable way.
     *
     * @see http://php.net/__set
     *
     * @param    string         the name of the member field
     *
     * @return mixed            the value for the member field
     */
    public function __get($name)
    {
        return $this->get_attribute($name);
    }

    /**
     * __isset() is a magic method triggered by calling isset() or empty() on
     * inaccessible members.
     * In this class it is used to check for attributes for the template in a
     * comfortable way.
     *
     * @see http://php.net/__set
     *
     * @param    string         the name of the member field
     *
     * @return bool             TRUE if that attribute exists, FALSE otherwise
     */
    public function __isset($name)
    {
        return isset($this->_attributes[$name]);
    }

    /**
     * __unset() is a magic method invoked when unset() is used on inaccessible
     * members.
     * In this class it is used to check for attributes for the template in a
     * comfortable way.
     *
     * @see http://php.net/__set
     *
     * @param    string         the name of the member field
     */
    public function __unset($name)
    {
        $this->clear_attribute($name);
    }

    /**
     * Parse, render and return the presentation.
     *
     * @param array  An optional associative array of attributes and their
     *                           associated values.
     * @param string A name of a layout template.
     *
     * @return string A string representing the rendered presentation.
     */
    public function render($attributes = null, $layout = null)
    {
        if (isset($layout)) {
            $this->set_layout($layout);
        }

        # merge attributes
        $this->set_attributes($attributes);

        return $this->_render();
    }

    /**
     * Parse, render and return the presentation.
     *
     * @return string A string representing the rendered presentation.
     */
    abstract public function _render();

    /**
     * Returns the value of an attribute.
     *
     * @param string An attribute name.
     * @param mixed  An attribute value.
     *
     * @return mixed    An attribute value.
     */
    public function get_attribute($name)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }

    /**
     * Set an array of attributes.
     *
     * @return array An associative array of attributes and their associated
     *                           values.
     */
    public function get_attributes()
    {
        return $this->_attributes;
    }

    /**
     * Set an attribute.
     *
     * @param string An attribute name.
     * @param mixed  An attribute value.
     */
    public function set_attribute($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * Set an array of attributes.
     *
     * @param array An associative array of attributes and their associated
     *                          values.
     */
    public function set_attributes($attributes)
    {
        $this->_attributes = (array) $attributes + (array) $this->_attributes;
    }

    /**
     * Clear all attributes associated with this template.
     */
    public function clear_attributes()
    {
        $this->_attributes = array();
    }

    /**
     * Clear an attribute associated with this template.
     *
     * @param string The name of the attribute to be cleared.
     */
    public function clear_attribute($name)
    {
        unset($this->_attributes[$name]);
    }

    /**
     * Set the template's layout.
     *
     * @param mixed A name of a layout template or a layout template.
     */
    public function set_layout($layout)
    {
        $this->_layout = $this->_factory->open($layout);
    }
}
