<?php

namespace DigressivePrice\Listener;

use DigressivePrice\Event\DigressivePriceEvent;
use DigressivePrice\Event\DigressivePriceFullEvent;
use DigressivePrice\Event\DigressivePriceIdEvent;
use DigressivePrice\Model\DigressivePrice;
use DigressivePrice\Model\DigressivePriceQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\Product\ProductEvent;

/**
 * Class CartAddListener
 * Manage actions when adding a product to a pack
 * 
 * @package DigressivePrice\Listener
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class DigressivePriceListener extends BaseAction implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::BEFORE_DELETEPRODUCT => array("beforeRemoveDigressivePrices", 128),
            TheliaEvents::CART_ADDITEM => array("itemAddedToCart", 128),
            TheliaEvents::CART_UPDATEITEM => array("itemAddedToCart", 128),
            'action.createDigressivePrice' => array("createDigressivePrice", 128),
            'action.updateDigressivePrice' => array("updateDigressivePrice", 128),
            'action.deleteDigressivePrice' => array("deleteDigressivePrice", 128));
    }

    /**
     * @param ProductEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function beforeRemoveDigressivePrices(ProductEvent $event)
    {
        $productId = $event->getProduct()->getId();

        DigressivePriceQuery::create()
            ->filterByProductId($productId)
            ->delete();
    }

    /**
     * @param CartEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function itemAddedToCart(CartEvent $event)
    {
        $productId = $event->getCartItem()->getProductId();
        $quantity = $event->getCartItem()->getQuantity();
        
        $dpq = DigressivePriceQuery::create()
                ->filterByProductId($productId)
                ->where('DigressivePrice.QuantityFrom <= ?', $quantity)
                ->where('DigressivePrice.QuantityTo >= ?', $quantity)
                ->find();

        if ($dpq->count() == 1) {
            // Change cart item's prices
            $cartItem = $event->getCartItem();
            $cartItem->setPrice($dpq[0]->getPrice());
            $cartItem->setPromoPrice($dpq[0]->getPromoPrice());
            $cartItem->save();
        }
    }

    /**
     * @param DigressivePriceEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function createDigressivePrice(DigressivePriceEvent $event)
    {
        $digressivePrice = new DigressivePrice();

        $digressivePrice
            ->setProductId($event->getProductId())
            ->setPrice($event->getPrice())
            ->setPromoPrice($event->getPromoPrice())
            ->setQuantityFrom($event->getQuantityFrom())
            ->setQuantityTo($event->getQuantityTo())
            ->save();
    }

    /**
     * @param DigressivePriceFullEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateDigressivePrice(DigressivePriceFullEvent $event)
    {
        $digressivePrice = DigressivePriceQuery::create()->findOneById($event->getId());

        $digressivePrice
            ->setProductId($event->getProductId())
            ->setPrice($event->getPrice())
            ->setPromoPrice($event->getPromoPrice())
            ->setQuantityFrom($event->getQuantityFrom())
            ->setQuantityTo($event->getQuantityTo())
            ->save();
    }

    /**
     * @param DigressivePriceIdEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteDigressivePrice(DigressivePriceIdEvent $event)
    {
        DigressivePriceQuery::create()
            ->filterById($event->getId())
            ->delete();
    }
}