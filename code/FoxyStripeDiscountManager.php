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
            $discountGrid = GridField::create('ProductDiscountTiers', 'Product Discounts', $this->ProductDiscountTiers(), $config);
            $fields->addFieldToTab('Root.Discounts', $discountGrid);
        }
    }
}
