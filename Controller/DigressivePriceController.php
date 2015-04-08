<?php

namespace DigressivePrice\Controller;

use DigressivePrice\DigressivePrice;
use DigressivePrice\Model\DigressivePriceQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use DigressivePrice\Event\DigressivePriceEvent;
use DigressivePrice\Event\DigressivePriceFullEvent;
use DigressivePrice\Event\DigressivePriceIdEvent;
use DigressivePrice\Form\CreateDigressivePriceForm;
use DigressivePrice\Form\UpdateDigressivePriceForm;
use DigressivePrice\Form\DeleteDigressivePriceForm;

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
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'DigressivePrice', AccessManager::CREATE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $cdpf = new CreateDigressivePriceForm($request);
        $session = $this->getSession();

        // Form verification
        try {
            $form = $this->validateForm($cdpf);

            // Check if the end of range quantity is greater or equal to the beginning one
            if ($form->get('quantityFrom')->getData() <= $form->get('quantityTo')->getData()) {

                // Find the product's others digressive prices
                $productDigressivePrices = DigressivePriceQuery::create()
                    ->findByProductId($form->get('productId')->getData());


                if (count($productDigressivePrices) != 0) {

                    $quantityFrom = $form->get('quantityFrom')->getData();
                    $quantityTo = $form->get('quantityTo')->getData();
                    $rangeVerified = 0;

                    foreach ($productDigressivePrices as $pdp) {

                        // Check if the range begins or ends in another range
                        if (($pdp->getQuantityFrom() <= $quantityFrom && $quantityFrom <= $pdp->getQuantityTo())
                            || ($pdp->getQuantityFrom() <= $quantityTo && $quantityTo <= $pdp->getQuantityTo())
                        ) {
                            $session->getFlashBag()->set('msg', Translator::getInstance()->trans('One of your values is included into an existing range'), [], DigressivePrice::DOMAIN);
                            break;
                        }

                        // Check if the range doesn't surround another range
                        if ($quantityFrom <= $pdp->getQuantityFrom() && $pdp->getQuantityTo() <= $quantityTo) {
                            $session->getFlashBag()->set('msg', Translator::getInstance()->trans('Your new range surrounds an existing one'), [], DigressivePrice::DOMAIN);
                            break;
                        } else {
                            $rangeVerified++;
                        }
                    }

                    if ($rangeVerified == count($productDigressivePrices)) {

                        // Dispatch create
                        $event = new DigressivePriceEvent(
                            $form->get('productId')->getData(),
                            $form->get('price')->getData(),
                            $form->get('promo')->getData(),
                            $form->get('quantityFrom')->getData(),
                            $form->get('quantityTo')->getData()
                        );
                        $this->dispatch('action.createDigressivePrice', $event);
                    }
                }
            }

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $form->get("productId")->getData(),
                    'current_tab' => 'modules'
                )
            );
        }
        catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }
    
    public function updateAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'DigressivePrice', AccessManager::UPDATE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $udpf = new UpdateDigressivePriceForm($request);
        $session = $this->getSession();

        // Form verification
        try {
            $form = $this->validateForm($udpf);

            // Check if the end of range quantity is greater or equal to the beginning one
            if ($form->get('quantityFrom')->getData() <= $form->get('quantityTo')->getData()) {

                // Find the product's others digressive prices
                $productDigressivePrices = DigressivePriceQuery::create()
                    ->filterById($form->get('id')->getData(), Criteria::NOT_IN)
                    ->findByProductId($form->get('productId')->getData());

                $quantityFrom = $form->get('quantityFrom')->getData();
                $quantityTo = $form->get('quantityTo')->getData();
                $rangeVerified = 0;

                foreach ($productDigressivePrices as $pdp) {

                    // Check if the range begins or ends in another range
                    if ( ($pdp->getQuantityFrom() <= $quantityFrom && $quantityFrom <= $pdp->getQuantityTo())
                        || ($pdp->getQuantityFrom() <= $quantityTo && $quantityTo <= $pdp->getQuantityTo()) ) {
                        $session->getFlashBag()->set('msg', Translator::getInstance()->trans('One of your values is included into an existing range'), [], DigressivePrice::DOMAIN);
                        break;
                    }

                    // Check if the range doesn't surround another range
                    if ($quantityFrom <= $pdp->getQuantityFrom() && $pdp->getQuantityTo() <= $quantityTo) {
                        $session->getFlashBag()->set('msg', Translator::getInstance()->trans('Your new range surrounds an existing one'), [], DigressivePrice::DOMAIN);
                        break;
                    } else {
                        $rangeVerified++;
                    }
                }

                if ($rangeVerified == count($productDigressivePrices)) {

                    // Dispatch update
                    $event = new DigressivePriceFullEvent(
                        $form->get('id')->getData(),
                        $form->get('productId')->getData(),
                        $form->get('price')->getData(),
                        $form->get('promo')->getData(),
                        $form->get('quantityFrom')->getData(),
                        $form->get('quantityTo')->getData()
                    );
                    $this->dispatch('action.updateDigressivePrice', $event);
                }
            }

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $form->get("productId")->getData(),
                    'current_tab' => 'modules'
                )
            );
        }
        catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }
    
    public function deleteAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'DigressivePrice', AccessManager::DELETE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $ddpf = new DeleteDigressivePriceForm($request);

        // Form verification
        try {
            $form = $this->validateForm($ddpf);

            // Dispatch and delete
            $event = new DigressivePriceIdEvent($form->get('id')->getData());
            $this->dispatch('action.deleteDigressivePrice', $event);

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $form->get("productId")->getData(),
                    'current_tab' => 'modules'
                )
            );
        } catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }
}
