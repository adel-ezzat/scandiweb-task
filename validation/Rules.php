<?php

namespace validation;

use database\Database;

/**
 * validation Rules
 */
class Rules
{

    protected array $errors = [];

    /**
     * check if attribute is exists
     * @param array $productAttribute
     * @return void
     */
    public function required(array $productAttribute)
    {
        if (in_array($productAttribute['value'], ['', null])) {
            $this->errors[$productAttribute['name']][] = "Please, provide {$productAttribute['name']}";
        }
    }

    /**
     * check if attribute is unique
     * @param array $productAttribute
     * @return void
     */
    public function unique(array $productAttribute)
    {
        if ($productAttribute['value']) {
            $records = (new Database('products'))
                ->select([$productAttribute['name']])
                ->where([
                    ['sku', '=', $productAttribute['value']]
                ])->get();

            if (count($records) > 0) {
                $this->errors[$productAttribute['name']][] = 'field should be unique';
            }
        }
    }

    /**
     * check if attribute is number
     * @param array $productAttribute
     * @return void
     */
    public function number(array $productAttribute)
    {
        if ($productAttribute['value']) {
            if (!is_numeric($productAttribute['value']) || $productAttribute['value'] > 0) {
                $this->errors[$productAttribute['name']][] = 'Please, provide the data of indicated type';
            }
        }
    }

    /**
     * check if attribute is string
     * @param array $productAttribute
     * @return void
     */
    public function string(array $productAttribute)
    {
        if ($productAttribute['value']) {
            if (!is_string($productAttribute['value'])) {
                $this->errors[$productAttribute['name']][] = 'Please, provide the data of indicated type';
            }
        }
    }

}