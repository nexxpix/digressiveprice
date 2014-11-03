<?php

namespace DigressivePrice\Form;

use Symfony\Component\Validator\Constraints;
use Thelia\Form\BaseForm;

/**
 * Class UpdateDigressivePriceForm
 * Build form to update a digressive price
 * 
 * @package DigressivePrice\Form
 * @author Nexxpix
 */
class UpdateDigressivePriceForm extends BaseForm
{

    protected function buildForm()
    {
        $this->formBuilder
        ->add("id", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        )
        ->add("productId", "number", array(
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
                    new Constraints\NotBlank()
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

    public function getName()
    {
        return "digressiveprice_update";
    }

}
