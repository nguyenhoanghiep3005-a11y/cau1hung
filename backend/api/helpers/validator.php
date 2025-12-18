<?php
function validate_student(array $data, bool $for_update = false): array
{
    $errors = [];

    if (!$for_update || array_key_exists('name', $data)) {
        if (empty($data['name'] ?? null)) {
            $errors['name'] = 'Name is required.';
        }
    }

    if (array_key_exists('email', $data) && !empty($data['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }
    }

    if (array_key_exists('class_id', $data) && $data['class_id'] !== null && $data['class_id'] !== '') {
        if (!is_numeric($data['class_id'])) {
            $errors['class_id'] = 'class_id must be an integer or null.';
        }
    }

    return $errors;
}
