<?php

declare(strict_types=1);

namespace App\Controller\V1\User\Join;

use App\Module\User\Command\JoinByEmail\Request\Command;
use App\Module\User\Command\JoinByEmail\Request\Handler;
use App\Validation\ValidationErrorsBuilder;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestAction
 * @package App\Controller\V1\User\Join
 */
class RequestAction extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var ValidationErrorsBuilder
     */
    private ValidationErrorsBuilder $validationErrorsBuilder;
    /**
     * @var SerializerInterface
     */
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
     * @Route("/auth/signup", name="home", methods={"POST"})
     *
     * @ParamConverter(
     *     "command",
     *     converter="fos_rest.request_body"
     * )
     *
     * @param Command $command
     * @param ConstraintViolationListInterface $validationErrors
     * @param Handler $handler
     * @return View
     */
    public function request(
        Command $command,
        ConstraintViolationListInterface $validationErrors,
        Handler $handler
    ): View {
        $errors = $this->validationErrorsBuilder->build($validationErrors);

        if (!empty($errors)) {
            return View::create($errors, Response::HTTP_BAD_REQUEST);
        }

        $handler->handle($command);

        return View::create([], Response::HTTP_CREATED);
    }
}
