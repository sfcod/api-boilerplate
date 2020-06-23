<?php

namespace App\Service;

use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class FormUtility
 *
 * @author Orlov Alexey <orlov.alexey@zfort.com>
 *
 * @package Portal\Shared\Service
 */
class FormUtility
{
    /**
     * @param FormInterface|ConstraintViolationListInterface $data
     * @param string $title
     *
     * @return array
     *
     * @throws Exception
     */
    public function formatErrors($data, $title = 'An error occurred')
    {
        $detail = '';
        $violations = [];
        switch (true) {
            case $data instanceof FormInterface:
                $errors = $this->getErrorsAsArray($data);
                foreach ($errors as $key => $message) {
                    $violations[] = [
                        'propertyPath' => $key,
                        'message' => reset($message),
                    ];
                    $detail .= sprintf("%s: %s\n", $key, reset($message));
                }
                break;
            case $data instanceof ConstraintViolationListInterface:
                $errors = $this->getConstraintViolationsAsArray($data);
                foreach ($errors as $key => $message) {
                    $violations[] = [
                        'propertyPath' => $key,
                        'message' => $message,
                    ];
                    $detail .= sprintf("%s: %s\n", $key, $message);
                }
                break;
            default:
                throw new Exception('Data should be instanceof FormInterface or ConstraintViolationListInterface');
        }

        return [
            'title' => $title,
            'detail' => $detail,
            'violations' => $violations,
        ];
    }

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
