<?php

namespace validation;

class DVDValidation
{
    public function validate($request): array
    {
        {
            return (new validator($request, [
                'size' => 'required|number'
            ]))->validate();
        }
    }
}