<?php

namespace DigressivePrice\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use DigressivePrice\Form\CreateDigressivePriceForm;
use DigressivePrice\Form\UpdateDigressivePriceForm;
use DigressivePrice\Form\DeleteDigressivePriceForm;
use DigressivePrice\Model\DigressivePrice;
use DigressivePrice\Model\DigressivePriceQuery;
use Thelia\Tools\URL;

/**
 * Class DigressivePriceController
 * Manage actions of DigressivePrice module
 * 
 * @package DigressivePrice\Controller
 * @author Nexxpix
 */
class DigressivePriceController extends BaseAdminController
{
    public function createAction()
    {
        // Initialize vars
        $request = $this->getRequest();
        $cdpf = new CreateDigressivePriceForm($request);
        $form = $cdpf->getForm();

        // Form verification
        $form->bind($request);

        if ($form->isValid()) {
            
            $digressivePrice = new DigressivePrice();
            $digressivePrice->setProductId($form->get('productId')->getData());
            $digressivePrice->setQuantityFrom($form->get('quantityFrom')->getData());
            $digressivePrice->setQuantityTo($form->get('quantityTo')->getData());
            $digressivePrice->setPrice($form->get('price')->getData());
            $digressivePrice->setPromoPrice($form->get('promo')->getData());
            $digressivePrice->save();
            
        }
        $this->redirect(URL::getInstance()->absoluteUrl($this->getRoute(
                                'admin.products.update', array('product_id' => $form->get("productId")->getData()))));
    }
    
    public function updateAction()
    {
        // Initialize vars
        $request = $this->getRequest();
        $udpf = new UpdateDigressivePriceForm($request);
        $form = $udpf->getForm();

        // Form verification
        $form->bind($request);

        if ($form->isValid()) {
            
            DigressivePriceQuery::create()
                    ->filterById($form->get('id')->getData())
                    ->update(array(
                        'ProductId' => $form->get('productId')->getData(),
                        'QuantityFrom' => $form->get('quantityFrom')->getData(),
                        'QuantityTo' => $form->get('quantityTo')->getData(),
                        'Price' => $form->get('price')->getData(),
                        'PromoPrice' => $form->get('promo')->getData()));
        }
        $this->redirect(URL::getInstance()->absoluteUrl($this->getRoute(
                                'admin.products.update', array('product_id' => $form->get("productId")->getData()))));
    }
    
    public function deleteAction()
    {
        // Initialize vars
        $request = $this->getRequest();
        $ddpf = new DeleteDigressivePriceForm($request);
        $form = $ddpf->getForm();

        // Form verification
        $form->bind($request);

        if ($form->isValid()) {
            
            DigressivePriceQuery::create()
            ->filterById($form->get('id')->getData())
            ->delete();
            
        }
        $this->redirect(URL::getInstance()->absoluteUrl($this->getRoute(
                                'admin.products.update', array('product_id' => $form->get("productId")->getData()))));
    }
}
