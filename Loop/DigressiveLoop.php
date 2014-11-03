<?php

namespace DigressivePrice\Loop;

use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Argument\Argument;
use DigressivePrice\Model\DigressivePriceQuery;
use Thelia\Model\ProductQuery;
use Thelia\Model\CountryQuery;
use Thelia\Model\TaxQuery;
use Thelia\Model\TaxRule;
use Thelia\Model\TaxRuleQuery;
use Thelia\Model\TaxRuleCountry;
use Thelia\Model\TaxRuleCountryQuery;

/**
 * Class DigressiveLoop
 * Definition of the Digressive loop of DigressivePrice module
 * 
 * @package DigressivePrice\Loop
 * @author Nexxpix
 */
class DigressiveLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{

    public $countable = true;

    protected function getArgDefinitions()
    {
        return new ArgumentCollection(Argument::createIntListTypeArgument('product_id'));
    }

    public function buildModelCriteria()
    {
        $productId = $this->getProductId();
        $search = DigressivePriceQuery::create();

        if (!is_null($productId)) {
            $search->filterByProductId($productId);
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $digressivePrice) {

            $loopResultRow = new LoopResultRow($digressivePrice);

            // Get product
            $productId = $digressivePrice->getProductId();
            $product = ProductQuery::create()->findOneById($productId);

            // Get prices
            $price = $digressivePrice->getPrice();
            $promo = $digressivePrice->getPromoPrice();

            // get country
            $country = CountryQuery::create()->findOneById(64);

            // Get taxed prices
            $taxedPrice = $product->getTaxedPrice($country, $price);
            $taxedPromoPrice = $product->getTaxedPromoPrice($country, $promo);

            $loopResultRow
                    ->set("ID", $digressivePrice->getId())
                    ->set("PRODUCT_ID", $productId)
                    ->set("QUANTITY_FROM", $digressivePrice->getQuantityFrom())
                    ->set("QUANTITY_TO", $digressivePrice->getQuantityTo())
                    ->set("PRICE", $price)
                    ->set("PROMO_PRICE", $promo)
                    ->set("TAXED_PRICE", $taxedPrice)
                    ->set("TAXED_PROMO_PRICE", $taxedPromoPrice);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

}
