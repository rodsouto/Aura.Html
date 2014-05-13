<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html\Helper;

/**
 * 
 * Helper for a stack of <script> tags.
 * 
 * @package Aura.Html
 * 
 */
class Scripts extends AbstractHelper
{
    /**
     * 
     * The array of all scripts added to the helper.
     * 
     * @var array
     * 
     */
    protected $scripts = array();

    /**
     * 
     * Returns the stack of <script> tags as a single block.
     * 
     * @return string The <script> tags.
     * 
     */
    public function __toString()
    {
        $html = '';
        ksort($this->scripts);
        foreach ($this->scripts as $scripts) {
            foreach ($scripts as $script) {
                $html .= $this->indent . $script . PHP_EOL;
            }
        }
        $this->scripts = array();
        return $html;
    }

    /**
     * 
     * Returns the helper so you can call methods on it.
     * 
     * @return self
     * 
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * 
     * Adds a <script> tag to the stack.
     * 
     * @param string $src The source href for the script.
     * 
     * @param int $pos The script position in the stack.
     * 
     * @return null
     * 
     */
    public function add($src, $pos = 100)
    {
        $attr = $this->escaper->attr(array(
            'src' => $src,
            'type' => 'text/javascript',
        ));
        
        $tag = "<script $attr></script>";
        $this->scripts[(int) $pos][] = $tag;
    }

    /**
     * 
     * Adds a conditional `<!--[if ...]><script><![endif] -->` tag to the 
     * stack.
     * 
     * @param string $cond The conditional expression for the script.
     * 
     * @param string $src The source href for the script.
     * 
     * @param string $pos The script position in the stack.
     * 
     * @return null
     * 
     */
    public function addCond($cond, $src, $pos = 100)
    {
        $cond = $this->escaper->html($cond);
        
        $attr = $this->escaper->attr(array(
            'src' => $src,
            'type' => 'text/javascript',
        ));

        $tag = "<!--[if $cond]><script $attr></script><![endif]-->";
        $this->scripts[(int) $pos][] = $tag;
    }
}
