<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Helper_TagTest extends EcomDev_PHPUnit_Test_Case
{
    protected $helper;
    protected function setUp()
    {
        parent::setUp();
        $this->helper = Mage::helper('helpdesk/tag');
    }

    /**
     * @test
     * @loadFixture data
     */
    public function test() {
    	$ticket = Mage::getModel('helpdesk/ticket')->load(2);
    	$this->helper->addTags($ticket, 'aaa, bbb');
    	$this->helper->addTags($ticket, 'ccc');

    	$this->assertEquals(array(1,2,3), $ticket->getTagIds());

    	$this->assertEquals('aaa, bbb, ccc', $this->helper->getTagsAsString($ticket));

    	$this->helper->removeTags($ticket, 'aaa, ccc');
    	$this->assertEquals(array(2), array_values($ticket->getTagIds()));
    }
}