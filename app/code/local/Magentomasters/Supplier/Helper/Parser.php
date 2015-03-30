<?php
class Magentomasters_Supplier_Helper_Parser extends Mage_Core_Helper_Abstract
{
	protected $variables;

    public function __construct($variables = array())
    {
        $this->variables = $variables;
    }

    protected function eval_block($matches)
    {
        if( is_array($this->variables) && count($this->variables) )
        {
            foreach($this->variables as $var_name => $var_value)
            {
                $$var_name = $var_value;
            }
        }

        $eval_end = '';

        if( $matches[1] == '<?=' || $matches[1] == '<?php=' )
        {
            if( $matches[2][count($matches[2]-1)] !== ';' )
            {
                $eval_end = ';';
            }
        }

        $return_block = '';

        eval('$return_block = ' . $matches[2] . $eval_end);

        return $return_block;
    }

    public function parse($string)
    {
        return preg_replace_callback('/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/', array(&$this, 'eval_block'), $string);
    }
}
	