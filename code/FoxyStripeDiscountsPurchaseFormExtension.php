<?php

class FoxyStripeDiscountsPurchaseFormExtension extends Extension
{
    /**
     * @param $form
     */
    public function updateFoxyStripePurchaseForm(&$form)
    {
        $code = $this->owner->Code;
        $fields = $form->Fields();
        if ($this->owner->DiscountTitle && $this->owner->ProductDiscountTiers()->exists()) {
            $fields->push(HiddenField::create(ProductPage::getGeneratedValue($code, 'discount_quantity_percentage',
                $this->owner->getDiscountFieldValue()))->setValue($this->owner->getDiscountFieldValue()));
        }
    }
}