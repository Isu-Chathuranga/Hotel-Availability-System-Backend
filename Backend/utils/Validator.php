<?php
namespace App\Utils;

class Validator
{
    private array $errors = [];

    public function required(string $field, $value, string $label = ''): self
    {
        $label = $label ?: $field;
        if (empty($value) && $value !== '0' && $value !== 0) {
            $this->errors[] = "{$label} is required";
        }
        return $this;
    }

    public function email(string $field, string $value): self
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format for {$field}";
        }
        return $this;
    }

    public function minLength(string $field, string $value, int $min): self
    {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[] = "{$field} must be at least {$min} characters";
        }
        return $this;
    }

    public function inArray(string $field, $value, array $allowed): self
    {
        if (!empty($value) && !in_array($value, $allowed)) {
            $this->errors[] = "{$field} must be one of: " . implode(', ', $allowed);
        }
        return $this;
    }

    public function numeric(string $field, $value): self
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[] = "{$field} must be a number";
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function clear(): void
    {
        $this->errors = [];
    }
}
