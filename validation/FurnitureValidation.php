<?php

namespace validation;

class FurnitureValidation
{
    public function validate($request): array
    {
        {
            return (new Validator($request, [
                'height' => 'required|number',
                'width' => 'required|number',
                'length' => 'required|number'
            ]))->validate();
        }
    }
}