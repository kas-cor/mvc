<?php

namespace src\Validator;

/**
 * Class Validator
 * @package src\Validator
 */
class Validator
{
    /**
     * @var array Errors list
     */
    private array $errors = [];

    /**
     * Validate required field
     * @param mixed $value
     * @param string $field
     * @param string $message
     * @return self
     */
    public function required($value, string $field, string $message = ''): self
    {
        if (empty($value) && $value !== '0') {
            $this->errors[$field] = $message ?: "Поле '{$field}' обязательно для заполнения";
        }
        return $this;
    }

    /**
     * Validate email
     * @param string $value
     * @param string $field
     * @param string $message
     * @return self
     */
    public function email(string $value, string $field, string $message = ''): self
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?: "Неверный формат e-mail";
        }
        return $this;
    }

    /**
     * Validate min length
     * @param string $value
     * @param int $min
     * @param string $field
     * @param string $message
     * @return self
     */
    public function minLength(string $value, int $min, string $field, string $message = ''): self
    {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = $message ?: "Минимальная длина '{$field}' - {$min} символов";
        }
        return $this;
    }

    /**
     * Validate max length
     * @param string $value
     * @param int $max
     * @param string $field
     * @param string $message
     * @return self
     */
    public function maxLength(string $value, int $max, string $field, string $message = ''): self
    {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = $message ?: "Максимальная длина '{$field}' - {$max} символов";
        }
        return $this;
    }

    /**
     * Validate password strength
     * @param string $value
     * @param string $field
     * @param string $message
     * @return self
     */
    public function passwordStrength(string $value, string $field, string $message = ''): self
    {
        if (!empty($value)) {
            $hasUppercase = preg_match('/[A-Z]/', $value);
            $hasLowercase = preg_match('/[a-z]/', $value);
            $hasDigit = preg_match('/[0-9]/', $value);
            
            if (!$hasUppercase || !$hasLowercase || !$hasDigit || strlen($value) < 8) {
                $this->errors[$field] = $message ?: "Пароль должен содержать минимум 8 символов, заглавную букву, строчную букву и цифру";
            }
        }
        return $this;
    }

    /**
     * Check if validation passed
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get all errors
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error
     * @return string|null
     */
    public function getFirstError(): ?string
    {
        return empty($this->errors) ? null : reset($this->errors);
    }

    /**
     * Clear errors
     * @return self
     */
    public function clear(): self
    {
        $this->errors = [];
        return $this;
    }
}
