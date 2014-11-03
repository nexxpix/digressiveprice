<?php

namespace DigressivePrice\Form;

use Symfony\Component\Validator\Constraints;
use Thelia\Form\BaseForm;

/**
 * Class DeleteDigressivePriceForm
 * Build form to delete a digressive price
 * 
 * @package DigressivePrice\Form
 * @author Nexxpix
 */
class DeleteDigressivePriceForm extends BaseForm
{

    protected function buildForm()
    {
        $this->formBuilder
        ->add("productId", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        )
        ->add("id", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank()
                )
            )
        );
    }

    public function getName()
    {
        return "digressiveprice_delete";
    }

}
