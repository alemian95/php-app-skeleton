<?php

namespace App\Modules\Auth;

use App\Entities\User;
use App\Modules\Users\UserService;
use Laminas\Diactoros\Response\JsonResponse;

class LoginValidation extends \Src\Validation\Validator
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

        /** @var User */
        $user = null;

        // check required
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

            $user = UserService::findByEmail($data['email']);
            if (! ($user instanceof User)) {
                $errors['email'][] = "doesnt_exist";
            }
        }

        if (empty($data['password'])) {
            $errors['password'][] = "required";
        } else {
            if ($user && ! $user->checkPassword($data['password'])) {
                $errors['password'][] = "invalid_credentials";
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