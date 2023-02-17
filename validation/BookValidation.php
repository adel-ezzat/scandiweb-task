<?php

namespace validation;

class BookValidation
{
    public function validate($request): array
    {
        {
            return (new validator($request, [
                'weight' => 'required|number'
            ]))->validate();
        }
    }
}