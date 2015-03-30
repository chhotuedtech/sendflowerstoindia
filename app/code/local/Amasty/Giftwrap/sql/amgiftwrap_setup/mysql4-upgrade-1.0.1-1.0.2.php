<?php
/**
 * @copyright  Amasty (http://www.amasty.com)
 */

$this->startSetup();


$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'amgiftwrap_blacklisted', array(
        'group'          => 'General',
        'type'           => 'int',
        'backend'        => '',
        'frontend_input' => '',
        'frontend'       => '',
        'label' => 'Disable Gift Wrap for This Item',
        'input'          => 'select',
        'class'          => '',
        'source'         => 'eav/entity_attribute_source_boolean',
        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'        => true,
        'frontend_class' => '',
        'required'       => false,
        'user_defined'   => true,
        'is_used_for_promo_rules' => true,
        'default'        => '0',
        'position'       => 100,
    )
);


$this->endSetup();