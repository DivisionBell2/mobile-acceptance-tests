<?php
/**
 * @description Блок выбора доставки товара
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Checkbox;
use Core\Utils\Arrays;

class DeliveryMethods extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'delivery-method';

    private function getDeliveryCheckboxes(): array
    {
        Waiting::waitForElementVisible(
            $this->me(),
            10,
            'Не удалось дождаться видимости блока способов доставки'
        );
        $deliveryCheckboxes = [];
        $deliveryOptionsSelector = $this->me()->childByTag('input', true);
        $deliveryOptionsSelectorsCount = WB::selectorCount($deliveryOptionsSelector);

        for ($i = 1; $i <= $deliveryOptionsSelectorsCount; $i++) {
            $selector = $deliveryOptionsSelector->nthOfType($i);
            if (WB::isElementExists($selector)) {
                $deliveryCheckboxes[] = new Checkbox((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($deliveryCheckboxes, 'Не найдены способы доставки на странице Checkout');
        return $deliveryCheckboxes;
    }

    public function getPickupCheckbox(): Checkbox
    {
        $checkboxes = $this->getDeliveryCheckboxes();

        /**
         * @var $checkboxes Checkbox[]
         */
        foreach ($checkboxes as $checkbox) {
            if (WB::isElementExists($checkbox->me()->havingAttribute('id', '28'))) {
                return $checkbox;
            }
        }
        throw new ATException('Не найден чекбокс самовывоза');
    }
}