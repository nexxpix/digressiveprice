<?php

namespace DigressivePrice\Form;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Class UpdateDigressivePriceForm
 * Build form to update a digressive price
 *
 * @package DigressivePrice\Form
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class UpdateDigressivePriceForm extends CreateDigressivePriceForm
{
    public function getName()
    {
        return "digressiveprice_update";
    }

    protected function buildForm()
    {
        parent::buildForm(true);

        $this->formBuilder
        ->add(
            "id",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank()
                ),
                "label" => 'ID'
            )
        );
    }

    public function fromNotInRange($value, ExecutionContextInterface $context, $isUpdating = true)
    {
        parent::fromNotInRange($value, $context, $isUpdating);
    }

    public function toNotInRange($value, ExecutionContextInterface $context, $isUpdating = true)
    {
        parent::toNotInRange($value, $context, $isUpdating);
    }

    public function notSurround($value, ExecutionContextInterface $context, $isUpdating = true)
    {
        parent::notSurround($value, $context, $isUpdating);
    }
}
