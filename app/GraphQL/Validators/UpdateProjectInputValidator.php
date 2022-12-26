<?php

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateProjectInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id' => ['exists:projects,id'],
            'name' => [
                'string',
                'max:255',
                Rule::unique('projects', 'name')->ignore($this->arg('id')),
            ],
        ];
    }
}
