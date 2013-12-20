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
        $this->_dotNotation = DotNotation::create (); 
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
        //$this->dot ()->from ([]);
        $this->dot ()->_get (['foo']);
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
        
        $this->dot ()->from ($sampleStructure);
        
        $this->assertEquals (
                $sampleStructure ['photos'], 
                $this->dot ()->_get (['photos'])
        );
        
        $this->assertEquals (
                $sampleStructure ['foo']['bar']['cats'], 
                $this->dot ()->_get (['foo', 'bar', 'cats'])
        );
    }
    
    public function testReadOnly ()
    {
        $this->dot ()->from (['my_private_cookies']);
        
        // nobody will ever get it!
        $this->dot ()->readOnly (true);
        
        $methods = ['remove', 'set', 'merge', 'from'];
        
        $exceptionsCounter = 0;
        
        foreach ($methods as $method)
        {
            try
            {
                // no matter what you pass - 
                // accessibility must be checked before performing anything
                $this->dot ()->{$method} (null, null);
            } 
            catch (LogicException $exception) 
            {
                $exceptionsCounter ++;
            }
        }
        
        $this->assertEquals ($exceptionsCounter, count ($methods));
    }
    
    public function testRemove ()
    {
        $sample = [
            'secret' => 42    ,
            'data'   => [
                'code'  => '007' ,
                'stuff' => [
                    'jeans' => 'blue',
                ],
            ],
        ];
        
        $this->dot ()->from ($sample);
        
        $this->dot ()->remove ('secret')->remove ('data.stuff.jeans');
        
        unset ($sample ['secret']); 
        unset ($sample ['data']['stuff']['jeans']);
        
        $this->assertEquals ($this->dot ()->root (), $sample);
    }
    
    public function testSet ()
    {
        // $this->dot ()->from ([]);
        $this->dot ()
            ->set ('foo', 'bar')
            ->set ('some.random.stuff', 42)
            ->set ('some.random.symbols', 'walehtyebo');
        
        $structure = [
            'foo'  => 'bar',
            'some' => [
                'random' => [
                    'stuff'   => 42           ,
                    'symbols' => 'walehtyebo' ,
                ],
            ],
        ];
        
        $this->assertEquals ($structure, $this->dot ()->root ());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    
    public function testFromValidation ()
    {
        $this->dot ()->from (null, null); 
    }
    
    public function testFrom ()
    {
        $structure = ['secret' => '42'];
        
        $this->dot ()->from ($structure); // array
        $this->assertEquals ($structure, $this->dot ()->root ());
        
        $this->dot ()->from (new DotNotation);
        $this->assertEquals ($this->dot ()->root (), []);
        
        $this->dot ()->from (json_encode ($structure), DotNotation::JSON);
        $this->assertEquals ($this->dot ()->root (), $structure);
        
        $this->dot ()
                ->from (serialize ($structure), DotNotation::PHP_SERIALIZED);
        $this->assertEquals ($this->dot ()->root (), $structure);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    
    public function testMergeValidation ()
    {
        $this->dot ()->merge (null);               
    }
    
    public function testMerge ()
    {
        $structure = [
            'blow' => 'fish',
            'data' => [
                'code' => '007'
            ]
        ];
        
        $this->dot ()->from ($structure);
        
        $anotherDot = new DotNotation;
        $anotherDot->from ([
            'data' => [
                'code' => 'unknown'
            ]
        ]);
        
        $this->dot ()->merge ([
            'blow' => 'foobar' // I wanted to assign something different, ehh
        ]);
        
        $this->dot ()->merge ($anotherDot);
        
        $structure ['blow'] = 'foobar';
        $structure ['data']['code'] = 'unknown';
        
        $this->assertEquals ($structure, $this->dot ()->root ());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    
    public function testToMethodValidation ()
    {
        $this->dot ()->to (null);
    }
    
    public function testToMethod ()
    {
        $structure = ['foo' => 'bar'];        
        $this->dot ()->from ($structure);
        
        $this->assertEquals (json_encode ($structure),
                                $this->dot ()->to (DotNotation::JSON));
        
        $this->assertEquals (serialize ($structure),
                            $this->dot ()->to (DotNotation::PHP_SERIALIZED));
        
        $this->assertEquals ($structure, 
                                $this->dot ()->to (DotNotation::PHP_ARRAY));
    }
    
    public function testArrayAccessImplementation ()
    {
        $dot = $this->dot ();        
        
        $dot ['foo.cats'] = 'bar';
        $dot ['foo.bar'] = 42;
        $dot ['my.favourite.fruit'] = 'apple';
        
        $this->assertTrue (isset ($dot ['foo.bar']));
        $this->assertEquals (6, count ($dot->root (), COUNT_RECURSIVE));
        
        $this->assertEquals ($dot ['my.favourite.fruit'], 'apple');        
        
        unset ($dot ['foo']);
        
        try
        {
            $dot ['foo'];
            // at least this won't stop execution
            $this->assertTrue (false);
        }
        catch (Exception $ex) 
        {
            $this->assertTrue (true);
        }
    }
    
} // end DotNotationTest

