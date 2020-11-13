<?php

namespace Brain\Validation;

class ValidationError
{
    private $rule;
    private $key;
    private $attr;
    private $messages = [
        'notempty' => 'le champ %s est requis',
        'max' => 'le champ %s doit contenir moins de %d caractères',
        'min' => 'le champ %s doit contenir plus de %d caractères',
        'email' => 'le champ %s est incorrect'
    ];

    /**
     *
     * @param string $key
     * @param string $rule
     */
    public function __construct(string $key, string $rule, array $attr)
    {
        $this->key = $key;

        $this->rule = $rule;

        $this->attr = $attr;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $param = array_merge([$this->key], $this->attr);
        return sprintf($this->messages[$this->rule], ...$param);
    }
}