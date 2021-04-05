<?php

namespace App\Core\Stubs\Entities;

class StubReplacement
{

    private string $type = 'string';

    private string $variableName;

    private $default = null;

    private string $question;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return StubReplacement
     */
    public function setType(string $type): StubReplacement
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return $this->variableName;
    }

    /**
     * @param string $variableName
     * @return StubReplacement
     */
    public function setVariableName(string $variableName): StubReplacement
    {
        $this->variableName = $variableName;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null $default
     * @return StubReplacement
     */
    public function setDefault($default): StubReplacement
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return StubReplacement
     */
    public function setQuestion(string $question): StubReplacement
    {
        // TODO Not string, be able to ask the question using choice/ask/any other method (callback), along with validation.
        $this->question = $question;
        return $this;
    }

}
