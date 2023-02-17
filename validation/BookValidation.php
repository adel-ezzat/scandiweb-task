<?php

namespace validation;

class BookValidation
{
    public function validate($request): array
    {
        {
            return (new Validator($request, [
                'weight' => 'required|number'
            ]))->validate();
        }
    }
}