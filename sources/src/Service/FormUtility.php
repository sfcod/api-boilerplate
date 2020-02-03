<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class FormUtility
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package Portal\Shared\Service
 */
class FormUtility
{
    /**
     * Get form errors as array("field_name" => "error_message")
     */
    public function getErrorsAsArray(FormInterface $form): array
    {
        $errors = [];

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $error) {
                $errors['form'][] = $error->getMessage();
            }
        }

        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors(true) as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }

    public function getConstraintViolationsAsArray(ConstraintViolationListInterface $list): array
    {
        $errors = [];

        foreach ($list as $violation) {
            /* @var ConstraintViolationInterface $violation */
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
