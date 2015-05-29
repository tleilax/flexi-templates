<?php
namespace Flexi;

/**
 * @author    Marcus Lunzenauer <mlunzena@uos.de>
 * @author    Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @copyright 2008-2015 Marcus Lunzenauer
 * @license   MIT license http://opensource.org/licenses/MIT
 * @version   0.6.0
 * @package   Flexi
 */

class PhpTemplate extends Template
{
    /**
     * Parse, render and return the presentation.
     *
     * @return string A string representing the rendered presentation.
     */
    public function _render()
    {
        extract($this->_attributes);

        # include template, parse it and get output
        ob_start();
        require $this->_template;
        $content_for_layout = ob_get_clean();

        # include layout, parse it and get output
        if (isset($this->_layout)) {
            $defined = get_defined_vars();
            unset($defined['this']);
            $content_for_layout = $this->_layout->render($defined);
        }

        return $content_for_layout;
    }

    /**
     * Parse, render and return the presentation of a partial template.
     *
     * @param string A name of a partial template.
     * @param array  An optional associative array of attributes and their
     *                           associated values.
     *
     * @return string A string representing the rendered presentation.
     */
    public function render_partial($partial, $attributes = array())
    {
        return $this->_factory->render($partial, $attributes + $this->_attributes);
    }

    /**
     * Renders a partial template with every member of a collection. This member
     * can be accessed by a template variable with the same name as the name of
     * the partial template.
     *
     * Example:
     *
     *   # template entry.php contains:
     *   <li><?= $entry ?></li>
     *
     *
     *   $entries = array('lorem', 'ipsum');
     *   $template->render_partial_collection('entry', $entries);
     *
     *   # results in:
     *   <li>lorem</li>
     *   <li>ipsum</li>
     *
     * TODO (mlunzena) spacer and attributes must be described
     *
     * @param string A name of a partial template.
     * @param array  The collection to be rendered.
     * @param string Optional a name of a partial template used as spacer.
     * @param array  An optional associative array of attributes and their
     *                           associated values.
     *
     * @return string A string representing the rendered presentation.
     */
    public function render_partial_collection($partial, $collection, $spacer = null, $attributes = array())
    {
        $template = $this->_factory->open($partial);
        $template->set_attributes($this->_attributes);
        $template->set_attributes($attributes);

        $collected = array();
        $iterator_name = pathinfo($partial, PATHINFO_FILENAME);
        foreach ($collection as $element) {
            $collected[] = $template->render(array($iterator_name => $element));
        }

        $spacer = isset($spacer) ? $this->render_partial($spacer, $attributes) : '';

        return implode($spacer, $collected);
    }
}
