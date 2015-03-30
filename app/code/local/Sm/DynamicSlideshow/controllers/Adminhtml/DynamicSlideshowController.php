<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Adminhtml_DynamicSlideshowController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu( 'dynamicslideshow' )->_addBreadcrumb( Mage::helper( 'adminhtml' )->__( 'Manager Sliders' ), Mage::helper( 'adminhtml' )->__( 'Manager Sliders' ) );
        $this->_title( Mage::helper( 'dynamicslideshow' )->__( 'Sm Dynamic SlideShow' ) );
        return $this;
    }
    
    public function indexAction()
    {
        $this->_initAction();
        $this->_title( Mage::helper( 'dynamicslideshow' )->__( 'Manage Sliders' ) );
        $this->_addContent( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_sliders' ) );
        $this->renderLayout();
    }
    
    public function editAction()
    {
        $id    = $this->getRequest()->getParam( 'id' );
        $model = Mage::getModel( 'dynamicslideshow/sliders' )->load( $id );
        if ( $model->getId() || $id == 0 )
        {
            $data = Mage::getSingleton( 'adminhtml/session' )->getFormData( true );
            if ( !empty( $data ) )
            {
                $model->setData( $data );
            }
            Mage::register( 'sliders', $model );
            
            $this->_initAction();
            $this->_title( Mage::helper( 'dynamicslideshow' )->__( 'Manager Sliders' ) );
            
            if ( $model->getId() )
            {
                $this->_title( $model->getTitle() );
            }
            else
            {
                $this->_title( Mage::helper( 'dynamicslideshow' )->__( 'New Slider' ) );
            }
            
            $this->getLayout()->getBlock( 'head' )->setCanLoadExtJs( true );
            
            $this->_addContent( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_sliders_edit' ) )->_addLeft( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_sliders_edit_tabs' ) );
            
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton( 'adminhtml/session' )->addError( Mage::helper( 'dynamicslideshow' )->__( 'Item does not exist' ) );
            $this->_redirect( '*/*/' );
        }
    }
    
    public function newAction()
    {
        $this->_forward( 'edit' );
    }
    
    public function saveAction()
    {
        if ( $data = $this->getRequest()->getPost() )
        {
            if ( isset( $data['form_key'] ) )
                unset( $data['form_key'] );
            $model = Mage::getModel( 'dynamicslideshow/sliders' );
            $model->setData( $data )->setId( $this->getRequest()->getParam( 'id' ) );
            try
            {
                $model->setParams( Mage::helper( 'core' )->jsonEncode( $data ) );
                $model->save();
                Mage::getSingleton( 'adminhtml/session' )->addSuccess( Mage::helper( 'dynamicslideshow' )->__( 'Item was successfully saved' ) );
                Mage::getSingleton( 'adminhtml/session' )->setFormData( false );
                if ( $this->getRequest()->getParam( 'back' ) )
                {
                    $this->_redirect( '*/*/edit', array(
                         'id' => $model->getId(),
                        'activeTab' => $this->getRequest()->getParam( 'activeTab' ) 
                    ) );
                    return;
                }
                $this->_redirect( '*/*/' );
                return;
            }
            catch ( Exception $e )
            {
                Mage::getSingleton( 'adminhtml/session' )->addError( $e->getMessage() );
                Mage::getSingleton( 'adminhtml/session' )->setFormData( $data );
                $this->_redirect( '*/*/edit', array(
                     'id' => $this->getRequest()->getParam( 'id' ) 
                ) );
                return;
            }
        }
        Mage::getSingleton( 'adminhtml/session' )->addError( Mage::helper( 'dynamicslideshow' )->__( 'No data found to save' ) );
        $this->_redirect( '*/*/' );
    }
    
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam( 'id' ) > 0 )
        {
            try
            {
                $dynamicslideshowModel = Mage::getModel( 'dynamicslideshow/sliders' );
                $dynamicslideshowModel->setId( $this->getRequest()->getParam( 'id' ) )->delete();
                Mage::getSingleton( 'adminhtml/session' )->addSuccess( Mage::helper( 'adminhtml' )->__( 'Item was successfully deleted' ) );
                $this->_redirect( '*/*/' );
            }
            catch ( Exception $e )
            {
                Mage::getSingleton( 'adminhtml/session' )->addError( $e->getMessage() );
                $this->_redirect( '*/*/edit', array(
                     'id' => $this->getRequest()->getParam( 'id' ) 
                ) );
            }
        }
        $this->_redirect( '*/*/' );
    }
    
    public function addSlidesAction()
    {
        $sliders   = Mage::getModel( 'dynamicslideshow/sliders' );
        $slides    = Mage::getModel( 'dynamicslideshow/slides' );
        $slidersId = $this->getRequest()->getParam( 'sid', null );
        $slidesId  = $this->getRequest()->getParam( 'id', null );
        if ( is_numeric( $slidersId ) )
            $sliders->load( $slidersId );
        if ( is_numeric( $slidesId ) )
            $slides->load( $slidesId );
        Mage::register( 'sliders', $sliders );
        Mage::register( 'slides', $slides );
        $this->_initAction();
        $this->getLayout()->getBlock( 'head' )->addItem( 'link_rel', $this->getUrl( 'dynamicslideshow/index/getCssCaptions' ), 'type="text/css" rel="stylesheet"' );
        $this->_title( Mage::helper( 'dynamicslideshow' )->__( 'Manage Sliders' ) );
        if ( $slides->getId() )
            $this->_title( $slides->getTitle() );
        else
            $this->_title( Mage::helper( 'dynamicslideshow' )->__( 'New Slides' ) );
        $this->_addContent( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_slides_edit' ) )->_addLeft( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_slides_edit_tabs' ) );
        
        $this->renderLayout();
    }
    
    public function massDeleteAction()
    {
        $slidersIDs = $this->getRequest()->getParam( 'dynamicslideshow' );
        if ( !is_array( $slidersIDs ) )
        {
            Mage::getSingleton( 'adminhtml/session' )->addError( Mage::helper( 'adminhtml' )->__( 'Please select item(s)' ) );
        }
        else
        {
            try
            {
                foreach ( $slidersIDs as $slidersID )
                {
                    $sliders = Mage::getModel( 'dynamicslideshow/sliders' )->load( $slidersID );
                    $sliders->delete();
                }
                Mage::getSingleton( 'adminhtml/session' )->addSuccess( Mage::helper( 'adminhtml' )->__( 'Total of %d record(s) were successfully deleted', count( $slidersIDs ) ) );
            }
            catch ( Exception $e )
            {
                Mage::getSingleton( 'adminhtml/session' )->addError( $e->getMessage() );
            }
        }
        $this->_redirect( '*/*/index' );
    }
    
    public function saveSlidesAction()
    {
        if ( $data = $this->getRequest()->getPost() )
        {
            if ( $data['form_key'] )
                unset( $data['form_key'] );
            if ( $data['layers'] )
            {
                $layers      = $data['layers'];
                $arrayLayers = Mage::helper( 'core' )->jsonDecode( $layers );
                function order_sort( $a, $b )
                {
                    if ( !isset( $a['order'] ) || !isset( $b['order'] ) )
                        return 0;
                    return $a['order'] - $b['order'];
                }
                usort( $arrayLayers, 'order_sort' );
                unset( $data['layers'] );
                $model = Mage::getModel( 'dynamicslideshow/slides' );
                $model->setSliderid( $data['sliderid'] );
                if ( isset( $data['fullwidth_centering'] ) )
                {
                    $data['fullwidth_centering'] = true;
                }
                $model->setParams( Mage::helper( 'core' )->jsonEncode( $data ) );
                $model->setLayers( Mage::helper( 'core' )->jsonEncode( $arrayLayers ) );
                if ( isset( $data['id'] ) && is_numeric( $data['id'] ) )
                {
                    $model->setId( $data['id'] );
                }
                else
                {
                    $numSlides = Mage::getModel( 'dynamicslideshow/sliders' )->getSlideCount( $data['sliderid'] );
                    $model->setSlideOrder( $numSlides + 1 );
                }
                try
                {
                    $model->save();
                    $url = $this->getUrl( '*/*/edit', array(
                         'id' => $data['sliderid'],
                        'activeTab' => 'slides_section' 
                    ) );
                    $this->getResponse()->setBody( $url );
                }
                catch ( Exception $e )
                {
                }
            }
        }
    }
    
    public function videoAction()
    {
        $this->loadLayout( 'overlay_popup' );
        $this->renderLayout();
    }
    
    public function cssAction()
    {
        $id    = $this->getRequest()->getParam( 'style', null );
        $model = Mage::getModel( 'dynamicslideshow/css' )->load( $id );
        Mage::register( 'css', $model );
        $this->loadLayout( 'overlay_popup' );
        $this->renderLayout();
    }
    
    public function animationAction()
    {
        $this->loadLayout( 'overlay_popup' );
        $aid   = $this->getRequest()->getParam( 'aid' );
        $model = Mage::getModel( 'dynamicslideshow/animation' );
        if ( strpos( $aid, 'custom' ) === 0 )
        {
            $part = explode( '-', $aid );
            $model->load( $part[1] );
            $model->setAnimSpeed( 500 );
        }
        Mage::register( 'animation', $model );
        $this->renderLayout();
    }
    
    public function saveAnimationAction()
    {
        if ( $this->getRequest()->isPost() )
        {
            $data = $this->getRequest()->getPost();
            if ( isset( $data['form_key'] ) )
                unset( $data['form_key'] );
            $model = Mage::getModel( 'dynamicslideshow/animation' );
            if ( isset( $data['id'] ) && $data['id'] )
            {
                $model->load( $data['id'] );
            }
            if ( isset( $data['name'] ) && $data['name'] )
            {
                $model->setName( $data['name'] );
                unset( $data['name'] );
                $model->setParams( Mage::helper( 'core' )->jsonEncode( $data ) );
                try
                {
                    $model->save();
                    $out['success'] = 1;
                    $out['data']    = array(
                         'id' => $model->getId(),
                        'name' => $model->getName(),
                        'params' => Mage::helper( 'core' )->jsonDecode( $model->getParams() ) 
                    );
                    $this->getResponse()->setBody( Mage::helper( 'core' )->jsonEncode( $out ) );
                }
                catch ( Exception $e )
                {
                    
                }
            }
        }
    }
    
    public function deleteAnimationAction()
    {
        if ( $this->getRequest()->isPost() )
        {
            $id    = $this->getRequest()->getParam( 'id' );
            $model = Mage::getModel( 'dynamicslideshow/animation' )->load( $id );
            if ( $model->getId() )
            {
                try
                {
                    $model->delete();
                    $out = array(
                         'success' => 1,
                        'id' => $id 
                    );
                    $this->getResponse()->setBody( Mage::helper( 'core' )->jsonEncode( $out ) );
                }
                catch ( Exception $e )
                {
                    
                }
            }
        }
    }
    
    public function deleteCssAction()
    {
        if ( $this->getRequest()->isPost() )
        {
            $id    = $this->getRequest()->getParam( 'id' );
            $model = Mage::getModel( 'dynamicslideshow/css' )->load( $id );
            if ( $model->getId() )
            {
                try
                {
                    $model->delete();
                    $this->getResponse()->setBody( Mage::helper( 'core' )->jsonEncode( array(
                         'success' => 1 
                    ) ) );
                }
                catch ( Exception $e )
                {
                    $this->getResponse()->setBody( Mage::helper( 'core' )->jsonEncode( array(
                         'error' => 1 
                    ) ) );
                }
            }
        }
    }
    
    public function saveCssAction()
    {
        if ( $this->getRequest()->isPost() )
        {
            $helper = Mage::helper( 'core' );
            $data   = $this->getRequest()->getPost();
            if ( isset( $data['form_key'] ) )
                unset( $data['form_key'] );
            if ( isset( $data['id'] ) )
            {
                $model = Mage::getModel( 'dynamicslideshow/css' )->load( $data['id'] );
                if ( $model->getId() )
                {
                    try
                    {
                        unset( $data['id'] );
                        if ( isset( $data['hover'] ) )
                        {
                            $hover = array();
                            $model->setSettings( $helper->jsonEncode( array(
                                 'hover' => 1 
                            ) ) );
                            foreach ( $data as $k => $v )
                            {
                                if ( strpos( $k, '__' ) === 0 )
                                {
                                    $hover[str_replace( '__', '', $k )] = $v;
                                    unset( $data[$k] );
                                }
                            }
                            $model->setHover( $helper->jsonEncode( $hover ) );
                            unset( $data['hover'] );
                        }
                        else
                            $model->setSettings( null );
                        $model->setParams( $helper->jsonEncode( $data ) );
                        $model->save();
                        $this->getResponse()->setBody( $helper->jsonEncode( array(
                             'success' => 1 
                        ) ) );
                    }
                    catch ( Exception $e )
                    {
                        $this->getResponse()->setBody( $helper->jsonEncode( array(
                             'error' => 1 
                        ) ) );
                    }
                }
            }
            elseif ( isset( $data['name'] ) )
            {
                $model = Mage::getModel( 'dynamicslideshow/css' );
                $model->setName( $data['name'] );
                $model->setHandle( '.tp-caption.' . $data['name'] );
                unset( $data['name'] );
                try
                {
                    if ( isset( $data['hover'] ) )
                    {
                        $hover = array();
                        $model->setSettings( $helper->jsonEncode( array(
                             'hover' => 1 
                        ) ) );
                        foreach ( $data as $k => $v )
                        {
                            if ( strpos( $k, '__' ) === 0 )
                            {
                                $hover[str_replace( '__', '', $k )] = $v;
                                unset( $data[$k] );
                            }
                        }
                        $model->setHover( $helper->jsonEncode( $hover ) );
                        unset( $data['hover'] );
                    }
                    else
                        $model->setSettings( null );
                    $model->setParams( $helper->jsonEncode( $data ) );
                    $model->save();
                    $this->getResponse()->setBody( $helper->jsonEncode( array(
                         'success' => 1,
                        'id' => $model->getId(),
                        'name' => $model->getName() 
                    ) ) );
                }
                catch ( Exception $e )
                {
                    $this->getResponse()->setBody( $helper->jsonEncode( array(
                         'error' => 1 
                    ) ) );
                }
            }
        }
    }
    
    public function deleteSlideAction()
    {
        $id    = $this->getRequest()->getParam( 'id' );
        $sid   = $this->getRequest()->getParam( 'sid' );
        $model = Mage::getModel( 'dynamicslideshow/slides' );
        if ( is_numeric( $id ) )
            $model->load( $id );
        if ( $model->getId() )
        {
            $model->delete();
        }
        $this->_redirect( '*/*/edit', array(
             'id' => $sid,
            'activeTab' => 'slides_section' 
        ) );
    }
    
    
    
    public function ajaxSaveAction()
    {
        if ( $data = $this->getRequest()->getPost() )
        {
            $id    = isset( $data['entity'] ) ? (int) $data['entity'] : null;
            $attr  = isset( $data['attr'] ) ? $data['attr'] : null;
            $value = isset( $data['value'] ) ? (int) $data['value'] : null;
            $out   = array(
                 'message' => '',
                'value' => $value 
            );
            switch ( $attr )
            {
                case 'slide_order':
                    $model = Mage::getModel( 'dynamicslideshow/slides' )->load( $id );
                    if ( $model->getId() )
                    {
                        $model->setData( $attr, $value );
                        $model->save();
                    }
                    else
                    {
                        $out['message'] = Mage::helper( 'dynamicslideshow' )->__( 'Slide not avaiable' );
                    }
            }
            $this->getResponse()->setBody( json_encode( $out ) );
        }
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_sliders_grid' )->toHtml() );
    }
    
    public function slidesAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function slidesGridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_sliders_edit_tab_slides' )->toHtml() );
    }
    
}