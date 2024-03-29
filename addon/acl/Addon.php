<?php

/**
 * Acl_Addon
 *
 * @category   Addon
 * @package    addon.acl
 * @author     Mori Reo <mori.reo@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Acl_Addon extends Sabel_Object
{
  const VERSION = 1.0;
  
  public function execute($bus)
  {
    $bus->getProcessorList()
        ->insertNext("initializer", "acl", new Acl_Processor("acl"));
  }
}
