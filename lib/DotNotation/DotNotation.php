<?php

/*
 * This file is a part of DotNotation
 * Please check the LICENSE file for more info 
 */

/**
 * @package DotNotation
 * @author Ilya
 */

class DotNotation implements ArrayAccess
{
    
    // all data obtained is stored here
    private $_data = [];
    
    // supported data types 
    // avoid 1 & 0 in constants, variables and such things
    const PHP_SERIALIZED = 2;    
    const JSON           = 3;
    const PHP_ARRAY      = 4;
    
    public function offsetExists ($offset)
    {
        
    }

    public function offsetGet ($offset)
    {
        
    }

    public function offsetSet ($offset, $value)
    {
        
    }

    public function offsetUnset ($offset)
    {
        
    }

    public function root ()
    {
        return (array) $this->_data;
    }
    
    public function merge ($data)
    {
        
    }
    
    public function from ($content, $dataType)
    {
        
    }
    
    public function to ($dataType)
    {
        
    }
    
    // the "heart" of DotNotation
    public function _parsePath ($path)
    {
        if ( !is_string ($path) || !$path )
        {
            throw new InvalidArgumentException ();
        }
        
        $dot = '.';
        
        $result = explode ($dot, $path);
        
        return array_filter ($result, function ($element)
        {
            return (boolean) $element;
        });
    }
}

