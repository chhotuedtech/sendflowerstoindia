<?php
/*------------------------------------------------------------------------
 # SM Mega Menu - Version 1.1
 # Copyright (c) 2013 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Megamenu_Adminhtml_MenugroupController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('megamenu/menugroup')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('megamenu/menugroup')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('menugroup_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('megamenu/menugroup');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Group Manager'), Mage::helper('adminhtml')->__('Group Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Group News'), Mage::helper('adminhtml')->__('Group News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('megamenu/adminhtml_menugroup_edit'))
				->_addLeft($this->getLayout()->createBlock('megamenu/adminhtml_menugroup_edit_tabs'));
				
			
			
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('megamenu/menugroup');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
					
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				if(!$this->getRequest()->getParam('id')){
					Mage::dispatchEvent('megamenu_menugroup_save_after',array('menugroup'=>$model));
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('megamenu/menugroup');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $megamenuIds = $this->getRequest()->getParam('menugroup_param');
		//Zend_Debug::dump($megamenuIds);die;
        if(!is_array($megamenuIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($megamenuIds as $megamenuId) {
                    $megamenu = Mage::getModel('megamenu/menugroup')->load($megamenuId);
                    $megamenu->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($megamenuIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $megamenuIds = $this->getRequest()->getParam('menugroup_param');
        if(!is_array($megamenuIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($megamenuIds as $megamenuId) {
                    $megamenu = Mage::getSingleton('megamenu/menugroup')
                        ->load($megamenuId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($megamenuIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'menugroup.csv';
        $content    = $this->getLayout()->createBlock('megamenu/adminhtml_menugroup_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'menugroup.xml';
        $content    = $this->getLayout()->createBlock('megamenu/adminhtml_menugroup_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
	
	public function importFromCategoriesAction()
	{
		$categories = Mage::getModel('catalog/category')
			->getCollection()
			->addAttributeToSelect('*')
			->addPathFilter('^1/[0-9/]+')
			->getItems();
		foreach ($categories as $category) {
			if ( $category->getLevel() == 1 ){
				$root_name = $category->getName();
			}
		}						
		$errorMessage = Mage::helper('megamenu')->__('Unable to import categories.');
    	try {
    		$menugroup = Mage::getModel('megamenu/menugroup')
    			->getCollection()
    			->addFieldToFilter('title', $root_name)
    			->getItems();
			
    		if ( count($menugroup) == 0 ){
    			$newmenugroup = Mage::getModel('megamenu/menugroup')
    				->setTitle($root_name)
    				->setContent('Import from categories')
    				->setStatus(1)
					->setCreatedTime(now())
					->setUpdateTime(now())
    				->save();
					Mage::dispatchEvent('megamenu_menugroup_save_after',array('menugroup'=>$newmenugroup));
    			if ( ($menugroup_id = $newmenugroup->getId()) && ($menugroup_root_id = $newmenugroup->getGroupId()) ){
    				$cats = array();    				
    				foreach ($categories as $category) {
    					if ( $category->getLevel() < 2 ){
    						continue;
    					}
    					$c = new stdClass();
    					$c->name  = $category->getName();
    					$c->id    = $category->getId();
    					$c->level = $category->getLevel();
    					$c->parent_id = $category->getParentId();
    					$c->is_active = $category->getIsActive() ? 1 : 0;
						$cats[$c->id] = $c;
    				}
					
    				foreach($cats as $id => $c){
    					if (isset($cats[$c->parent_id])){
    						if (!isset($cats[$c->parent_id]->child)){
    							$cats[$c->parent_id]->child = array();
    						}
    						$cats[$c->parent_id]->child[] =& $cats[$id];
    					}
    				}
					
    				$menu_id = array();
					$last_child= array();
					
    				foreach($cats as $id => $c){
    					if ( !isset($cats[$c->parent_id]) ){
    						$stack = array($cats[$id]);
    						while( count($stack)>0 ){
    							$opt = array_pop($stack);
								
    							$newmenu = Mage::getModel('megamenu/menuitems');
    							$newmenu->setTitle( $opt->name );
    							$newmenu->setStatus( $opt->is_active ) ;
    							
								$parent_id = !isset($menu_id[$opt->parent_id]) ? $menugroup_root_id : $menu_id[$opt->parent_id];
								$newmenu->setParentId( $parent_id );
    							
								if ( isset($last_child[$parent_id]) ){
										$newmenu->setPositionItem(Sm_Megamenu_Model_System_Config_Source_Position::AFTER);
										$newmenu->setOrderItem( $last_child[$parent_id] );
								}
								
								$newmenu->setGroupId( $menugroup_id );
    							$newmenu->setType( Sm_Megamenu_Model_System_Config_Source_Type::CATEGORY );
    							$newmenu->setDataType( "category/{$opt->id}" );
								$newmenu->setTarget( 2 );
								$newmenu->setColsNb( 6 );
								Mage::dispatchEvent('megamenu_menuitems_setItemLR_before',array('menuitems'=>$newmenu));	
    							$newmenu->save();
								Mage::dispatchEvent('megamenu_menuitems_updateItemsLR_after',array('menuitems'=>$newmenu));
								
    							$menu_id[$opt->id] = $newmenu->getId();
    							$last_child[$parent_id] = $newmenu->getId();
								
    							if (isset($opt->child) && count($opt->child)){
    								foreach(array_reverse($opt->child) as $child){
    									array_push($stack, $child);
    								}
    							}
								
    						}
    					}
    				}
    				$this->_getSession()->addSuccess( Mage::helper('megamenu')->__('Import success.') );
    			}
    		} else {
    			$this->_getSession()->addError( Mage::helper('megamenu')->__('Categories can be imported once.') );
    		}
    		
    	} catch (Mage_Core_Exception $e) {
    		$this->_getSession()->addError($errorMessage . ' ' . $e->getMessage());
    	} catch (Exception $e) {
    		$this->_getSession()->addError($errorMessage . ' ' . $e->getMessage());
    		Mage::logException($e);
    	}
    	$this->_redirect('*/*');
	}
}