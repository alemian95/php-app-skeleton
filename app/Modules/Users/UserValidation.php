<?php

namespace App\Modules\Users;

use Laminas\Diactoros\Response\JsonResponse;

class UserValidation extends \Src\Validation\Validator
{

    public function validateBody(): bool|\Psr\Http\Message\ResponseInterface|null
    {

        $body = $this->request->getParsedBody();

        $errors = [];

        // check required
        if (empty($body['name'])) $errors['name'][] = "required";
        if (empty($body['email'])) $errors['email'][] = "required";
        if (empty($body['password'])) $errors['password'][] = "required";

        // check email
        if (! $this->validEmail($body['email'])) $errors['email'][] = "email";

        if (count($errors)) {
            return new JsonResponse([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        return true;
    }

    public function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}