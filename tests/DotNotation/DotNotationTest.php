<?php

/*
 * This file is a part of DotNotation
 * Please check the LICENSE file for more info 
 */

class DotNotationTest extends PHPUnit_Framework_TestCase
{
    // stands for Dot Notation
    private $_dotNotation;
    
    public function setUp ()
    {
        $this->_dotNotation = new DotNotation (); 
    }
    
    public function tearDown ()
    {
        $this->_dotNotation = null;
    }
    
    private function dot ()
    {
        return $this->_dotNotation; // easier to change
    }
    
    public function testArrayAccessImplementation ()
    {
        
    }
}

