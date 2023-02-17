<?php

namespace validation;

class FurnitureValidation
{
    public function validate($request): array
    {
        {
            return (new validator($request, [
                'height' => 'required|number',
                'width' => 'required|number',
                'length' => 'required|number'
            ]))->validate();
        }
    }
}