<?php

namespace DigressivePrice\Form;

use DigressivePrice\DigressivePrice;
use DigressivePrice\Model\DigressivePriceQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Form\BaseForm;

/**
 * Class CreateDigressivePriceForm
 * Build form to create a new digressive price
 *
 * @package DigressivePrice\Form
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class CreateDigressivePriceForm extends BaseForm
{
    public function getName()
    {
        return "digressiveprice_create";
    }

    protected function buildForm()
    {
        $this->formBuilder
        ->add(
            "productId",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank()
                ),
                "label" => $this->translator->trans('product ID', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "quantityFrom",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array(
                                    $this,
                                    "fromNotInRange"
                                )
                            )
                        )
                    )
                ),
                "label" => $this->translator->trans('FROM {quantity}', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "quantityTo",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array($this,
                                    "toIsGreaterThanFrom"
                                ),
                                array($this,
                                    "toNotInRange"
                                ),
                                array($this,
                                    "notSurround"
                                )
                            )
                        )
                    )
                ),
                "label" => $this->translator->trans('TO {quantity}', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "price",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank()
                ),
                "label" => $this->translator->trans('Price w/o taxes', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "promo",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank()
                ),
                "label" => $this->translator->trans('Sale price w/o taxes', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        );
    }

    /**
     * @param $value
     * @param ExecutionContextInterface $context
     */
    public function toIsGreaterThanFrom($value, ExecutionContextInterface $context)
    {
        $quantityFrom = $this->getForm()->getData()['quantityFrom'];

        if ($quantityFrom >= $value) {
            $context->addViolation($this->translator->trans('The end of range must be greater than the beginning', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    /**
     * @param $value
     * @param ExecutionContextInterface $context
     * @param bool $isUpdating
     */
    public function fromNotInRange($value, ExecutionContextInterface $context, $isUpdating = false)
    {
        $digressivePrices = $this->inRangeQuery($value, $isUpdating);

        if (count($digressivePrices) !== 0) {
            $context->addViolation($this->translator->trans('Your new range begins in another one', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    /**
     * @param $value
     * @param ExecutionContextInterface $context
     * @param bool $isUpdating
     */
    public function toNotInRange($value, ExecutionContextInterface $context, $isUpdating = false)
    {
        $digressivePrices = $this->inRangeQuery($value, $isUpdating);

        if (count($digressivePrices) !== 0) {
            $context->addViolation($this->translator->trans('Your new range ends in another one', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    /**
     * @param $value
     * @param ExecutionContextInterface $context
     * @param bool $isUpdating
     */
    public function notSurround($value, ExecutionContextInterface $context, $isUpdating = false)
    {
        // Check if the values are around FROM and TO quantities of an existing digressive price of the current product
        $digressivePricesQuery = DigressivePriceQuery::create()
            ->filterByProductId($this->getForm()->getData()['productId'])
            ->filterByQuantityFrom($this->getForm()->getData()['quantityFrom'], Criteria::GREATER_EQUAL)
            ->filterByQuantityTo($value, Criteria::LESS_EQUAL);

        // If it's an update, don't check itself
        if ($isUpdating) {
            $digressivePricesQuery->filterById($this->getForm()->getData()['id'], Criteria::NOT_IN);
        } else {
            // Else it's a new one, so we only check for the current product
            $digressivePricesQuery->filterByProductId($this->getForm()->getData()['productId']);
        }

        $digressivePrices = $digressivePricesQuery->find();

        if (count($digressivePrices) !== 0) {
            $context->addViolation($this->translator->trans('Your new range surrounds an existing one', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    /**
     * @param $value
     * @param $isUpdating
     * @return array|mixed|\Propel\Runtime\Collection\ObjectCollection
     */
    public function inRangeQuery($value, $isUpdating)
    {
        // Check if the value is between FROM and TO quantities of an existing digressive price of the current product
        $digressivePricesQuery = DigressivePriceQuery::create()
            ->filterByProductId($this->getForm()->getData()['productId'])
            ->filterByQuantityFrom($value, Criteria::LESS_EQUAL)
            ->filterByQuantityTo($value, Criteria::GREATER_EQUAL);

        // If it's an update, don't check itself
        if ($isUpdating) {
            $digressivePricesQuery->filterById($this->getForm()->getData()['id'], Criteria::NOT_IN);
        }

        return $digressivePricesQuery->find();
    }
}
