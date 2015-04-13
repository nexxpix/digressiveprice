<?php

namespace DigressivePrice\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
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
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class DigressivePriceController extends BaseAdminController
{
    /**
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function createAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'DigressivePrice', AccessManager::CREATE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $cdpf = new CreateDigressivePriceForm($request);

        try {
            $form = $this->validateForm($cdpf);

            // Dispatch create
            $event = new DigressivePriceEvent(
                $form->get('productId')->getData(),
                $form->get('price')->getData(),
                $form->get('promo')->getData(),
                $form->get('quantityFrom')->getData(),
                $form->get('quantityTo')->getData()
            );
            $this->dispatch('action.createDigressivePrice', $event);

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $form->get('productId')->getData(),
                    'current_tab' => 'modules'
                )
            );
        }
        catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }

    /**
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function updateAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'DigressivePrice', AccessManager::UPDATE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $udpf = new UpdateDigressivePriceForm($request);

        try {
            $form = $this->validateForm($udpf);

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

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $form->get('productId')->getData(),
                    'current_tab' => 'modules'
                )
            );
        }
        catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }

    /**
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'DigressivePrice', AccessManager::DELETE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $ddpf = new DeleteDigressivePriceForm($request);

        try {
            $form = $this->validateForm($ddpf);

            // Dispatch delete
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
