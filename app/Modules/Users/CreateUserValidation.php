<?php

namespace App\Modules\Users;

use App\Entities\User;
use Laminas\Diactoros\Response\JsonResponse;

class CreateUserValidation extends \Src\Validation\Validator
{

    /**
     * @return \Psr\Http\Message\ResponseInterface|array<string, mixed>
     */
    public function validateBody(): \Psr\Http\Message\ResponseInterface|array
    {

        $body = $this->request->getParsedBody();

        if (empty($body)) {
            return new JsonResponse([
                'success' => false
            ], 422);
        }

        /** @var array<string, mixed> */
        $data = is_object($body) ? json_decode(json_encode($body), true) : $body;

        $errors = [];

        // check required
        if (empty($data['name'])) $errors['name'][] = "required";

        if (empty($data['password'])) {
            $errors['password'][] = "required";
        } else if (empty($data['password_confirmation'])) {
            $errors['password_confirmation'] = "required";
        } else {
            if ($data['password'] !== $data['password_confirmation']) {
                $errors['password'][] = "confirmed";
            }
        }

        if (empty($data['email'])) {
            $errors['email'][] = "required";
        } else {
            // check email
            $validatedEmail = $this->validEmail($data['email']);
            if (! $validatedEmail) {
                $errors['email'][] = "email";
            } else {
                $data['email'] = $validatedEmail;
            }

            if (UserService::findByEmail($data['email']) instanceof User) {
                $errors['email'][] = "exists";
            }
        }

        if (count($errors)) {
            return new JsonResponse([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        return $data;
    }

    public function validEmail(string $email): mixed
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}