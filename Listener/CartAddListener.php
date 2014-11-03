<?php

namespace DigressivePrice\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Model\CartItem;
use DigressivePrice\Model\DigressivePriceQuery;

/**
 * Class CartAddListener
 * Manage actions when adding a product to a pack
 * 
 * @package DigressivePrice\Listener
 * @author Nexxpix
 */
class CartAddListener implements EventSubscriberInterface
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::CART_ADDITEM => array("itemAddedToCart", 128),
            TheliaEvents::CART_UPDATEITEM => array("itemAddedToCart", 128));
    }

    public function itemAddedToCart(CartEvent $event)
    {
        $productId = $event->getCartItem()->getProductId();
        $quantity = $event->getCartItem()->getQuantity();
        
        //$session = $this->request->getSession();
        //$cartItems = ($session->getCart()->getCartItems());
        
        /* In case the last range is managed with 0 value
         * Just a beginning, has to be impoved to select only the last range
         * If developped, think about display filters in front and back offices files
         * 
        $dpq = DigressivePriceQuery::create()
                ->filterByProductId($productId)
                ->condition('qtyFrom', 'DigressivePrice.QuantityFrom <= ?', $quantity)
                ->condition('qtyTo', 'DigressivePrice.QuantityTo >= ?', $quantity)
                ->combine(array('qtyFrom', 'qtyTo'), 'and', 'fromFixedToFixed')
                ->condition('qtyToInfinite', 'DigressivePrice.QuantityTo = ?', 0)
                ->combine(array('qtyFrom', 'qtyToInfinite'), 'and', 'fromFixedToInfinite')
                ->where(array('fromFixedToFixed', 'fromFixedToInfinite'), 'or')
                ->find();
         */
        
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
}