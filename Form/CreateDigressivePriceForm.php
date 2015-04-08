<?php

namespace DigressivePrice\Form;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Form\BaseForm;

/**
 * Class CreateDigressivePriceForm
 * Build form to create a new digressive price
 * 
 * @package DigressivePrice\Form
 * @author Nexxpix
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
            "productId", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        )
        ->add("quantityFrom", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        )
        ->add("quantityTo", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array($this,
                                    "verifyIsGreater")
                            )
                        )
                    )
                )
            )
        )
        ->add("price", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        )
        ->add("promo", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        );
    }

    public function verifyIsGreater($value, ExecutionContextInterface $context)
    {
        $quantityFrom = $this->getForm()->getData()['quantityFrom'];

        if ($quantityFrom >= $value) {
            $context->addViolation("The end of range must be greater than the beginning");
        }
    }
}
