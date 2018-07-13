<?php

namespace Ivoz\Provider\Domain\Model\CallCsv;

class CallCsvDto extends CallCsvDtoAbstract
{
    private $csvPath;

    public function getFileObjects()
    {
        return [
            'Csv'
        ];
    }

    /**
     * @return self
     */
    public function setCsvPath(string $path = null)
    {
        $this->csvPath = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsvPath()
    {
        return $this->csvPath;
    }
}


