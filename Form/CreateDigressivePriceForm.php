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
                "label" => $this->translator->trans('quantity : from', [], DigressivePrice::DOMAIN.'.bo.default')
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
                "label" => $this->translator->trans('quantity : to', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "price",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank()
                ),
                "label" => $this->translator->trans('default price', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "promo",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank()
                ),
                "label" => $this->translator->trans('promo price', [], DigressivePrice::DOMAIN.'.bo.default')
            )
        );
    }

    public function toIsGreaterThanFrom($value, ExecutionContextInterface $context)
    {
        $quantityFrom = $this->getForm()->getData()['quantityFrom'];

        if ($quantityFrom >= $value) {
            $context->addViolation($this->translator->trans('The end of range must be greater than the beginning', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    public function fromNotInRange($value, ExecutionContextInterface $context, $isUpdating = false)
    {
        $digressivePricesQuery = DigressivePriceQuery::create()
            ->filterByQuantityFrom($value, Criteria::LESS_EQUAL)
            ->filterByQuantityTo($value, Criteria::GREATER_EQUAL);

        if ($isUpdating) {
            $digressivePricesQuery->filterById($this->getForm()->getData()['id'], Criteria::NOT_IN);
        }

        $digressivePrices = $digressivePricesQuery->find();

        if (count($digressivePrices) !== 0) {
            $context->addViolation($this->translator->trans('Your new range begins in another one', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    public function toNotInRange($value, ExecutionContextInterface $context, $isUpdating = false)
    {
        $digressivePricesQuery = DigressivePriceQuery::create()
            ->filterByQuantityFrom($value, Criteria::LESS_EQUAL)
            ->filterByQuantityTo($value, Criteria::GREATER_EQUAL);

        if ($isUpdating) {
            $digressivePricesQuery->filterById($this->getForm()->getData()['id'], Criteria::NOT_IN);
        }

        $digressivePrices = $digressivePricesQuery->find();

        if (count($digressivePrices) !== 0) {
            $context->addViolation($this->translator->trans('Your new range ends in another one', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }

    public function notSurround($value, ExecutionContextInterface $context, $isUpdating = false)
    {
        $digressivePricesQuery = DigressivePriceQuery::create()
            ->filterByQuantityFrom($this->getForm()->getData()['quantityFrom'], Criteria::GREATER_EQUAL)
            ->filterByQuantityTo($value, Criteria::LESS_EQUAL);

        if ($isUpdating) {
            $digressivePricesQuery->filterById($this->getForm()->getData()['id'], Criteria::NOT_IN);
        }

        $digressivePrices = $digressivePricesQuery->find();

        if (count($digressivePrices) !== 0) {
            $context->addViolation($this->translator->trans('Your new range surrounds an existing one', [], DigressivePrice::DOMAIN.'.bo.default'));
        }
    }
}
