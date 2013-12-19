<?php

/*
 * This file is a part of DotNotation
 * Please check the LICENSE file for more info 
 * 
 * This file is not completed just yet; I have no time to do that
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
    
    public function testParsePathValidation ()
    {
        $wrongData = 
        [
            null ,
            ''   ,
            // add more if you wish
        ];
        
        $catchedExceptions = 0;
        
        foreach ($wrongData as $data)
        {
            try
            {
                $this->dot ()->_parsePath ($data);
            } 
            catch (Exception $exception)
            {
                $catchedExceptions ++;
            }
        }
        
        $this->assertEquals (count ($wrongData), $catchedExceptions);
    }
    
    public function testParsePath ()
    {
        $samples = 
        [
            // path     => expected result 
            'foo'       => ['foo']          ,
            'foo.bar'   => ['foo', 'bar']   ,
            '.foo.bar.' => ['foo', 'bar']   ,
        ];
        
        foreach ($samples as $path => $expectedResult)
        {
            $this->assertEquals ($expectedResult, 
                    $this->dot ()->_parsePath ($path));
        }        
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    
    public function testGetMethodValidation ()
    {
        $this->dot ()->_get ([], ['foo']);
    }
    
    public function testGetMethod ()
    {
        $sampleStructure = 
        [
            'foo' =>
            [
                'bar' => 
                [
                    'cats' => 42,
                    'dogs' => 43,
                ]
            ],
            
            'photos' => 'food',
        ];
        
        $this->assertEquals (
                $sampleStructure ['photos'], 
                $this->dot ()->_get ($sampleStructure, ['photos'])
        );
        
        $this->assertEquals (
                $sampleStructure ['foo']['bar']['cats'], 
                $this->dot ()->_get ($sampleStructure, ['foo', 'bar', 'cats'])
        );
    }
}

