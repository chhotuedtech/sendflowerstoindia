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


class Mirasvit_Helpdesk_Test_Model_PatternTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @test
     * @loadFixture data
     *
     * @doNotIndex catalog_product_price
     */
     public function basicTest() {
        $pattern = Mage::getModel('helpdesk/pattern')->load(2);
        $email = Mage::getModel('helpdesk/email')->load(2);
        $email2 = Mage::getModel('helpdesk/email')->load(3);
        $this->assertTrue($pattern->checkEmail($email));
        $this->assertFalse($pattern->checkEmail($email2));
     }

}
