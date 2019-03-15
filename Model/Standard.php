<?php
/**
 * Standard.php
 *
 * @category Cammino
 * @package  Cammino_Installments
 * @author   Cammino Digital <suporte@cammino.com.br>
 * @license  http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link     https://github.com/cammino/magento-installments
 */

class Cammino_Installments_Model_Standard extends Mage_Core_Model_Abstract
{

    /**
     * Function responsible for return all available installments
     *
     * @param float $value Product value
     *
     * @return array
     */
    public function getAllInstallments($value)
    {
        $maxInstallments = floatval(Mage::getStoreConfig("catalog/installments/max_installments"));
        $minInstallmentValue = floatval(Mage::getStoreConfig("catalog/installments/min_installment_value"));
        $installmentTax = floatval(Mage::getStoreConfig("catalog/installments/installment_tax"));
        $qty = 1.0;
        $installmentValue = floatval($value);

        $allInstallments = array();

        for ($i=1.0; $i <= $maxInstallments; $i++) {
            $futureValue = $this->applyTax($value, $i, $installmentTax);

            if (($futureValue/$i) >= $minInstallmentValue) {
                $allInstallments[] = array(
                    "qty" => $i,
                    "value" => $futureValue / $i,
                    "total" => $futureValue
                );
            } else {
                break;
            }
        }

        return $allInstallments;
    }

    /**
     * Function responsible for return max product installment
     *
     * @param float $value Product value
     *
     * @return array
     */
    public function getInstallments($value)
    {
        $maxInstallments = floatval(Mage::getStoreConfig("catalog/installments/max_installments"));
        $minInstallmentValue = floatval(Mage::getStoreConfig("catalog/installments/min_installment_value"));
        $installmentTax = floatval(Mage::getStoreConfig("catalog/installments/installment_tax"));
        $qty = 1.0;
        $installmentValue = floatval($value);

        for ($i=1.0; $i <= $maxInstallments; $i++) {
            $futureValue = $this->applyTax($value, $i, $installmentTax);
            if (($futureValue/$i) >= $minInstallmentValue) {
                $installmentValue = ($futureValue/$i);
                $qty = $i;
            } else {
                break;
            }
        }

        return array("qty" => $qty, "value" => $installmentValue);
    }

    /**
     * Function responsible for apply tax in installment
     *
     * @param float $value Product value
     * @param float $n     Number of installments
     * @param float $tax   Tax to be applied
     *
     * @return float
     */
    public function applyTax($value, $n, $tax)
    {
        for ($i=1; $i <= $n; $i++) {
            $value = $value + ($value * ($tax / 100));
        }

        return $value;
    }

}
?>