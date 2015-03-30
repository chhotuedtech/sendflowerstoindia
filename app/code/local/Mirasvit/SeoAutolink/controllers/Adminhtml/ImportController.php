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
 * @package   Advanced SEO Suite
 * @version   1.1.1
 * @build     803
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_SeoAutolink_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('seo');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Import Links'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('seoautolink/adminhtml_import_edit'));
        $this->renderLayout();
    }


   public function saveAction()
   {
        $data = $this->getRequest()->getParams();

        $uploader = new Mage_Core_Model_File_Uploader('file');
        $uploader->setAllowedExtensions(array('csv'));
        $uploader->setAllowRenameFiles(true);
        $path = Mage::getBaseDir('var').DS.'import';
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        try {
            $result = $uploader->save($path);
            $fullPath = $result['path'].DS.$result['file'];

            $csv = new Varien_File_Csv();
            $data = $csv->getData($fullPath);

            $items = array();
            if (count($data) > 1) {
                for ($i = 1; $i < count($data); $i++ ) {
                    $item = array();
                    for ($j = 0; $j < count($data[0]); $j++) {
                        if (isset($data[$i][$j]) && trim($data[$i][$j]) != '') {
                            $item[strtolower($data[0][$j])] = $data[$i][$j];
                        }
                    }
                    $items[] = $item;
                }
            }

            $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
            $table = $resource->getTableName('seoautolink/link');
            $table2 = $resource->getTableName('seoautolink/link_store');
            $i = 0;
            foreach ($items as $item) {
                if (!isset($item['keyword'])) {
                    continue;
                }
                $item = new Varien_Object($item);
                $query = "REPLACE {$table} SET
                    keyword = '".addslashes($item->getKeyword())."',
                    url = '".addslashes($item->getUrl())."',
                    url_target = '".addslashes($item->getUrlTarget())."',
                    is_nofollow = '".addslashes($item->getIsNofollow())."',
                    max_replacements = '".addslashes($item->getMaxReplacements())."',
                    occurence = '".addslashes($item->getOccurence())."',
                    is_active = '".addslashes($item->getIsActive())."';
                    REPLACE {$table2} SET
                        store_id = 0,
                        link_id = LAST_INSERT_ID();
                     ";

                $writeConnection->query($query);
                $i ++;
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(''.$i.' records were inserted or updated');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}