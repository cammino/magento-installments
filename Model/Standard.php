<?php
class Cammino_Installments_Model_Standard extends Mage_Core_Model_Abstract
{
	public function getInstallments($value)
	{
		$max_installments = floatval(Mage::getStoreConfig("catalog/installments/max_installments"));
		$min_installment_value = floatval(Mage::getStoreConfig("catalog/installments/min_installment_value"));
		$installment_tax = floatval(Mage::getStoreConfig("catalog/installments/installment_tax"));
		$qty = 1.0;
		$installment_value = floatval($value);

		for($i=1.0; $i <= $max_installments; $i++) {
			$future_value = $this->applyTax($value, $i, $installment_tax);
			if (($future_value/$i) >= $min_installment_value) {
				$installment_value = ($future_value/$i);
				$qty = $i;
			} else {
				break;
			}
		}

		return array("qty" => $qty, "value" => $installment_value); 
	}

	public function applyTax($value, $n, $tax)
	{
		for ($i=1; $i <= $n; $i++) {
			$value = $value + ($value * ($tax / 100));
		}
		return $value;
	}

}
?>