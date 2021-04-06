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
        $sectionData = [];
        if($confirmation = IO::confirm($this->getQuestionText(), $this->getDefault())) {

        }
        $completeArray[$this->getVariableName()] = $confirmation;
        return $sectionData;
    }

    public function validateType($value): bool
    {
        return is_array($value);
    }

    private function getReplacementValues(bool $useDefault): array
    {
        $sectionData = [];
        foreach($this->getReplacements() as $replacement) {
            $sectionData = $replacement->appendData($sectionData, $useDefault);
        }
        return $sectionData;
    }

    public function appendData(array $data = [], bool $useDefault = false): array
    {
        $useSection = $useDefault && $this->hasDefault() ? $this->getDefault() : $this->askQuestion();
        return array_merge($data, $useSection ? $this->getReplacementValues($useDefault) : [], [$this->getVariableName() => $useSection]);
    }

}
