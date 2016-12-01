<?php

class FoxyStripeDiscountManager extends DataExtension
{
    /**
     * @var array
     */
    private static $db = [
        'DiscountTitle' => 'Varchar(50)',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'ProductDiscountTiers' => 'ProductDiscountTier',
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        if (!$this->owner->DiscountTitle && $this->owner->ProductDiscountTiers()->exists()) {
            $fields->addFieldTotab(
                'Root.Discounts',
                new LiteralField(
                    "ProductDiscountHeaderWarning",
                    "<p class=\"message warning\">A discount title is required for FoxyCart to properly parse the value. 
                        The discounts will not be applied until a title is entered.</p>"
                )
            );
        }

        $fields->addFieldToTab(
            'Root.Discounts',
            TextField::create('DiscountTitle')->setTitle(_t('Product.DiscountTitle', 'Discount Title'))
        );

        if ($this->owner->ID) {
            // ProductDiscountTiers
            $config = GridFieldConfig_RelationEditor::create();
            $config->removeComponentsByType('GridFieldAddExistingAutocompleter');
            $config->removeComponentsByType('GridFieldDeleteAction');
            $config->addComponent(new GridFieldDeleteAction(false));
            $discountGrid = GridField::create('ProductDiscountTiers', 'Product Discounts', $this->owner->ProductDiscountTiers(), $config);
            $fields->addFieldToTab('Root.Discounts', $discountGrid);
        }
    }
}
