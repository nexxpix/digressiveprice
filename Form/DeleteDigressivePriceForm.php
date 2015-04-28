<?php

namespace DigressivePrice\Form;

use DigressivePrice\DigressivePrice;
use Symfony\Component\Validator\Constraints;
use Thelia\Form\BaseForm;

/**
 * Class DeleteDigressivePriceForm
 * Build form to delete a digressive price
 *
 * @package DigressivePrice\Form
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class DeleteDigressivePriceForm extends BaseForm
{

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

    public function getName()
    {
        return "digressiveprice_delete";
    }
}
