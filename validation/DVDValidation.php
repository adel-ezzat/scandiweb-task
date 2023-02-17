<?php

namespace validation;

class DVDValidation
{
    public function validate($request): array
    {
        {
            return (new Validator($request, [
                'size' => 'required|number'
            ]))->validate();
        }
    }
}