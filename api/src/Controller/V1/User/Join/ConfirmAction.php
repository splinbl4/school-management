<?php

declare(strict_types=1);

namespace App\Controller\V1\User\Join;

use App\Module\User\Command\JoinByEmail\Confirm\Command;
use App\Module\User\Command\JoinByEmail\Confirm\Handler;
use App\Validation\ValidationErrorsBuilder;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ConfirmAction
 * @package App\Controller\V1\User\Join
 */
class ConfirmAction extends AbstractController
{
    private ValidatorInterface $validator;

    private ValidationErrorsBuilder $validationErrorsBuilder;

    private SerializerInterface $serializer;

    public function __construct(
        ValidatorInterface $validator,
        ValidationErrorsBuilder $validationErrorsBuilder,
        SerializerInterface $serializer
    ) {
        $this->validator = $validator;
        $this->validationErrorsBuilder = $validationErrorsBuilder;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/auth/join/confirm/{token}", name="auth.join.confirm", methods={"POST"})
     *
     * @param string $token
     * @param Handler $handler
     * @return View
     */
    public function handle(
        string $token,
        Handler $handler
    ) {
        $command = new Command($token);
        $handler->handle($command);

        return View::create([], Response::HTTP_OK);
    }
}
