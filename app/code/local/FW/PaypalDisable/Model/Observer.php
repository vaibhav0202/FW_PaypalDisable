<?php
class FW_PaypalDisable_Model_Observer {

	public function disablePaymentMethod(Varien_Event_Observer $observer) {
		//LOAD OBSERVER EVENT DATA
		$event           = $observer->getEvent();
		//LOAD PAYMENT METHOD BEING ENABLED
		$method          = $event->getMethodInstance();
		//LOAD THE CURRENT RESULT STATUS OF THE PAYMENT METHOD
		$result          = $event->getResult();

		//CHECK FOR PAYPAL STANDARD PAYMENT METHOD
		if($method->getCode() == "paypal_standard"){
			//LOAD CART ITEMS
			$quote = $event->getQuote();
			//LOOP THRU CART ITEMS
			foreach ($quote->getAllItems() as $item) {
				//LOAD PRODUCT ATTRIBUTES
				$_item = Mage::getModel('catalog/product')->load($item->getProductId());
				//GET ENABLE PAYPAL ATTRIBUTE VALUE
				$enablePaypal = $_item->getData("enable_paypal");
				//CHECK IF ENABLE_PAYPAL ATTRIBUTES IS SET TO NO
				if(isset($enablePaypal) && $enablePaypal == 0){
					//SET PAYPAL METHOD RESULT STATUS TO FALSE
					$result->isAvailable = false;
					//No need to continue looping through products once one is found.
					break;
				}
			}
		}
	}
}