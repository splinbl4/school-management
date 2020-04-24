<?php
declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorsBuilder
{
    /**
     * @param null|ConstraintViolationListInterface ...$violationLists
     *
     * @return array
     */
    public function build(?ConstraintViolationListInterface ...$violationLists): array
    {
        $violationLists = array_filter($violationLists);
        $unitedList = new ConstraintViolationList();
        foreach ($violationLists as $list) {
            $unitedList->addAll($list);
        }

        $violationHash = [];
        /** @var ConstraintViolationInterface $validationError */
        foreach ($unitedList as $validationError) {
            $violationHash[$validationError->getPropertyPath()]['errors'][] = $validationError->getMessage();
        }

        $responseData = [];
        foreach ($violationHash as $path => $violation) {
            $responseData['errors'][] = array_merge(['fieldName' => $path], $violation);
        }

        return $responseData;
    }
}