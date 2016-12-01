<?php

class ProductDiscountTierTest extends FunctionalTest
{
    /**
     * @var array
     */
    protected static $fixture_file = array(
        'foxystripe-discounts/tests/fixtures.yml',
    );

	protected static $use_draft_site = true;

	function setUp(){
		parent::setUp();

		$productHolder = ProductHolder::create();
		$productHolder->Title = 'Product Holder';
		$productHolder->write();

		$product = $this->objFromFixture('ProductPage', 'product1');
		$product->ParentID = $productHolder->ID;
		$product->write();
	}

    public function logOut(){
        $this->session()->clear('loggedInAs');
    }

	public function testProductDiscountTierCreation(){
		$this->logInWithPermission('Product_CANCRUD');

		$discount = ProductPage::get()->first();

		$tier = $this->objFromFixture('ProductDiscountTier', 'fiveforten');
		$tier->ProductPageID = $discount->ID;
		$tier->write();
		$tierID = $tier->ID;

		$checkTier = ProductDiscountTier::get()->byID($tierID);

		$this->assertEquals($checkTier->Quantity, 5);
		$this->assertEquals($checkTier->Percentage, 10);

		$this->logOut();
	}

	public function testProductDiscountTierEdit(){
		$this->logInWithPermission('ADMIN');

		$discount = ProductPage::get()->first();

		$tier = $this->objFromFixture('ProductDiscountTier', 'fiveforten');
		$tier->ProductPageID = $discount->ID;
		$tier->write();
		$tierID = $tier->ID;
		$this->logInWithPermission('Product_CANCRUD');

		$tier->Quantity = 2;
		$tier->Percentage = 5;
		$tier->write();

		$checkTier = ProductDiscountTier::get()->byID($tierID);

		$this->assertEquals($checkTier->Quantity, 2);
		$this->assertEquals($checkTier->Percentage, 5);

		$this->logOut();
	}

	public function testProductDiscountTierDeletion(){
		$this->logInWithPermission('ADMIN');

		$discount = ProductPage::get()->first();

		$tier = $this->objFromFixture('ProductDiscountTier', 'fiveforten');
		$tier->ProductPageID = $discount->ID;
		$tier->write();
		$tierID = $tier->ID;

		$this->logOut();

		$this->logInWithPermission('Product_CANCRUD');

		$tier->delete();
		$this->assertTrue(!ProductDiscountTier::get()->byID($tierID));

		$this->logOut();
	}

}
