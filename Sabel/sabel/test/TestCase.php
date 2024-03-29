<?php

require_once ("PHPUnit/Framework/TestCase.php");

/**
 * Sabel_Test_TestCase
 *
 * @category   Test
 * @package    org.sabel.test
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Sabel_Test_TestCase extends PHPUnit_Framework_TestCase
{
  public function eq($from, $to)
  {
    $this->assertEquals($from, $to);
  }
  
  public function neq($from, $to)
  {
    $this->assertNotEquals($from, $to);
  }
  
  public function isTrue($bool)
  {
    $this->assertTrue($bool);
  }
  
  public function isFalse($bool)
  {
    $this->assertFalse($bool);
  }
  
  public function isNull($null)
  {
    $this->assertNull($null);
  }
  
  public function isNotNull($null)
  {
    $this->assertNotNull($null);
  }
}
