<?php

namespace DigressivePrice\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class DigressivePriceHook
 * @package DigressivePrice\Hook
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class DigressivePriceHook extends BaseHook {

    public function onProductTabContent(HookRenderEvent $event)
    {
        $event->add(
            $this->render('product-tab-content-hook.html')
        );
    }
}