<?php

namespace App\Core\Stubs\Replacements;

use App\Core\Contracts\Stubs\StubReplacement;
use App\Core\Helpers\IO\IO;

class SectionReplacement extends StubReplacement
{

    /**
     * @var StubReplacement[]
     */
    protected array $replacements = [];

    public function pushReplacement(StubReplacement $replacement): SectionReplacement
    {
        $this->replacements[] = $replacement;
        return $this;
    }

    /**
     * @param StubReplacement[] $stubReplacements
     * @return void
     */
    public function setReplacements(array $stubReplacements): void
    {
        $this->replacements = $stubReplacements;
    }

    /**
     * @return StubReplacement[]
     */
    public function getReplacements(): array
    {
        return $this->replacements;
    }

    protected function askQuestion(): array
    {
        $completeArray = [];
        if($confirmation = IO::confirm($this->getQuestionText(), $this->getDefault())) {
            foreach($this->getReplacements() as $replacement) {
                $completeArray[$replacement->getVariableName()] = $replacement->getValue();
            }
        }
        $completeArray[$this->getVariableName()] = $confirmation;
        return $completeArray;
    }

    public function validateType($value): bool
    {
        return is_array($value);
    }

    public function appendData(array $data): array
    {
        return array_merge($data, $this->askQuestion());
    }

}
