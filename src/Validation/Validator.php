<?php

namespace Brain\Validation;

class Validator
{
    private $params = [];
    private $errors = [];

    /**
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     *
     * @param string ...$keys
     * @return self
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if(is_null($value) || empty($value)) {
                $this->addError($key, 'notempty');
            }
        }
        return $this;
    }

    /**
     *
     * @param string $key
     * @return self
     */
    public function email(string $key): self
    {
        $value = (string) $this->getValue($key);
        $pattern = '/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/';

        if(!preg_match($pattern, $value)) {
            $this->addError($key, 'email');
        }
        return $this;
    }

    /**
     *
     * @param string ...$keys
     * @return self
     */
    public function length(string $key, ?int $min, ?int $max): self
    {
        $value = (string) $this->getValue($key);

        if(!is_null($max) && strlen($value) > $max) {
            $this->addError($key, 'max', [$max]);
        }

        if(!is_null($min) && strlen($value) < $min) {
            $this->addError($key, 'min', [$min]);
        }
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isValide(): bool
    {
        return empty($this->errors);
    }

    /**
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     *
     * @param string $key
     * @param string $rule
     * @return void
     */
    private function addError(string $key, string $rule, array $attr = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attr);
    }

    /**
     *
     * @param string $key
     * @return string|null
     */
    private function getValue(string $key): ?string
    {
        return array_key_exists($key, $this->params) ? $this->params[$key] : null;
    }
}