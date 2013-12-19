<?php

/*
 * This file is a part of DotNotation
 * Please check the LICENSE file for more info 
 * 
 * I use public methods instead of private/protected 
 * in purpose of better testing
 */

/**
 * @package DotNotation
 * @author Ilya
 */

class DotNotation implements ArrayAccess
{
    
    // all data obtained is stored here
    private $_data = [];
    
    // prevents the data from being changed externally
    private $_readOnly = false;
    
    // supported data types 
    // avoid 1 & 0 in constants, variables and such things
    const PHP_SERIALIZED = 2;    
    const JSON           = 3;
    const PHP_ARRAY      = 4;
    
    public function offsetExists ($offset)
    {
        // not so beautiful, thinking how to make it better 
        try
        {
            $this->get ($offset);
        }
        catch (InvalidArgumentException $exception)
        {
            return false;
        }
        catch (UnexpectedValueException $exception)
        {
            return false;
        }
        
        return true;
    }

    public function offsetGet ($offset)
    {
        return $this->get ($offset);
    }

    public function offsetSet ($offset, $value)
    {
        $this->checkAccess ();
        $this->set ($offset, $value);
    }

    public function offsetUnset ($offset)
    {
        $this->checkAccess ();
        $this->remove ($offset);
    }

    public function readOnly ($value = null)
    {
        if ( is_null ($value) )
        {
            return $this->_readOnly;
        }
        elseif ( is_bool ($value) )
        {
            $this->_readOnly = $value;
        }
        else
        {
            throw new InvalidArgumentException ();
        }
    }
    
    public function checkAccess ()
    {
        if ( $this->readOnly () )
        {
            throw new LogicException ();
        }
    }
    
    public function root ()
    {
        return (array) $this->_data;
    }
    
    public function merge ($data)
    {
        if ( $data instanceof DotNotation )
        {
            $data = $data->root ();
        }
                
        if ( is_array ($data) )
        {
            $this->_data = array_merge ($this->_data, $data);
        }
         else
        {
            throw new InvalidArgumentException ();
        }
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
        
        $result = array_filter ($result, function ($element)
        {
            return (boolean) $element;
        });
        
        // updating indexes...
        return array_values ($result);
    }
    
    // returns value or throws an exception
    public function _get ($data , array $path )
    {
        $pointer = $data;
        
        foreach ($path as $element)
        {
            if ( isset ( $pointer [$element] ) )
            {
                $pointer = $pointer [$element];
            }
             else
            {
                throw new UnexpectedValueException ();
            }
        }
        
        return $pointer;
    }
    
    // you should only use THIS METHOD, not the one above
    public function get ($path)
    {
        $path = $this->_parsePath ($path);
        return $this->_get ($this->_data , $path);
    }
    
    public function set ($path, $value)
    {
        
    }
    
    public function remove ($path)
    {
        
    }
}

