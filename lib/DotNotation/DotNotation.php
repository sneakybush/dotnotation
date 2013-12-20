<?php

/*
 * This file is a part of DotNotation
 * Please check the LICENSE file for more info 
 * 
 * I use public methods instead of private/protected 
 * in purpose of better testing
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
        $this->set ($offset, $value);
    }

    public function offsetUnset ($offset)
    {
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
        $this->checkAccess ();
        
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
    
    public function from ($content, $dataType = null)
    {
        $this->checkAccess ();
        
        if ( $content instanceof DotNotation )
        {
            $content = $content->root ();
        }
        
        if ( is_array ($content) )
        {
            $this->_data = $content;
            return true;
        }
        
        // $content is not a DotNotation object and $dataType is not set 
        if ( is_null ($dataType) )
        {
            throw new InvalidArgumentException ();
        }
        
        switch ($dataType)
        {
            case (DotNotation::PHP_SERIALIZED) :
                
                $this->_data = unserialize ($content);
                
            break;    
            
            case (DotNotation::JSON) :
                
                $this->_data = json_decode ($content);
                
            break;    
        
            default:
                
                throw new InvalidArgumentException ();  
                
        }        
    }
    
    public function to ($dataType)
    {
        switch ($dataType)
        {
            
            case (DotNotation::PHP_ARRAY) :
                
                $result = $this->root ();
                
            break;
        
            case (DotNotation::JSON) :
            
                $result = json_encode ($this->root ());
                
            break;    
        
            case (DotNotation::PHP_SERIALIZED) :
                
                $result = serialize ($this->root ());
                
            break;    
        
            default:
                
                throw new UnexpectedValueException ();                   
        
        }
        
        return $result;
    }
    
    // the "heart" of DotNotation
    public function _parsePath ($path)
    {
        if ( !is_string ($path) || !$path )
        {
            throw new InvalidArgumentException ();
        }
        
        $dot = '.';        
        
        $result = array_filter (explode ($dot, $path), function ($element)
        {
            return ((boolean) $element) && ('.' != $element);
        });
        
        // updating indexes...
        $result = array_values ($result);
        
        if (0 == count ($result))
        {
            throw new InvalidArgumentException ();
        }
        
        return $result;
    }
    
    // returns value or throws an exception
    public function _get ( array $path )
    {
        $pointer =& $this->_data;
        
        foreach ($path as $element)
        {
            if ( isset ( $pointer [$element] ) )
            {
                $pointer =& $pointer [$element];
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
        return $this->_get ($path);
    }
    
    public function set ($path, $value)
    {
        $this->checkAccess ();
        
        $path = $this->_parsePath ($path);
        
        // later
    }
    
    public function remove ($path)
    {
        $this->checkAccess ();
        
        // working on you
    }
}

