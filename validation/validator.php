<?php

namespace validation;

/**
 * validation
 */
class validator extends rules
{
    protected array $productAttributes;
    protected array $rules = [
        'type'  => 'required',
        'sku'   => 'required|unique',
        'name'  => 'required|string',
        'price' => 'required|number'
    ];

    /**
     * @param $productAttributes
     * @param $rules
     */
    public function __construct($productAttributes, $rules)
    {
        $this->productAttributes = $productAttributes;
        $this->rules             = array_merge($this->rules, $rules);
    }

    /**
     * validate request with rules
     * @return array
     */
    public function validate(): array
    {
        foreach ($this->rules as $key => $rule) {
            $separatedRules = explode('|', $rule);
            foreach ($separatedRules as $separatedRule) {
                $this->$separatedRule(['name' => $key, 'value' => $this->productAttributes[$key] ?? '']);
            }
        }

        return $this->getErrors();
    }

    /**
     * get validation errors
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}