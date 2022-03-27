<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ValidationException extends Exception
{
    protected Validator $validator;

    /**
     * Create a new validation exception.
     */
    public function __construct(Validator $validator, ?string $message = null)
    {
        parent::__construct($message ?? 'Validation Failed.');

        $this->validator = $validator;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function render($request): JsonResponse
    {
        return new JsonResponse($this->getResponseData($request), HTTP_VALIDATION_ERROR);
    }

    /**
     * Prepare the response data.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function getResponseData($request): array
    {
        return [
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->validationErrorData($request),
        ];
    }

    /**
     * Format the validation error data to also include the rejected values.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function validationErrorData($request): array
    {
        $normalizedMessages = array_unique(
            Arr::dot($this->validator->errors()->getMessages())
        );

        $result = new Collection([]);

        collect($normalizedMessages)->each(function ($message, $key) use (&$result, $request) {
            $field = substr($key, 0, strpos($key, '.'));

            if (! $result->has($field)) {
                $result = $result->put($field, [
                    'message' => $message,
                    'rejected_value' => $request->input($field),
                ]);
            }
        });

        return $result->all();
    }

    /**
     * Get exception validator.
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
