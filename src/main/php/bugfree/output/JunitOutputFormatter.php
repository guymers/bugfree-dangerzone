<?php

namespace bugfree\output;


use bugfree\ErrorType;

class JunitOutputFormatter implements OutputFormatter
{
    private $resultXml = '';
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function begin($expectedTests)
    {
        $this->resultXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $this->resultXml .= "<testsuites>\n";
        $this->resultXml .= "\t<testsuite testcase=\"Bugfree Dangerzone type checks\" tests=\"$expectedTests\">\n";
    }

    public function testPassed($testNumber, $filename)
    {
        $this->resultXml .= "\t\t<testcase name=\"$filename\" />\n";
    }

    public function testFailed($testNumber, $filename, array $errors)
    {
        $this->resultXml .= "\t\t<testcase name=\"$filename\">\n";
        foreach ($errors as $error) {
            $type = 'Error';
            if ($error->severity == ErrorType::WARNING) {
                $type = 'Warning';
            }

            $this->resultXml .= "\t\t\t<failure type=\"$type\">{$error->getFormatted()}</failure>\n";
        }
        $this->resultXml .= "\t\t</testcase>\n";
    }

    public function end($status)
    {
        $this->resultXml .= "\t</testsuite>\n";
        $this->resultXml .= "</testsuites>\n";
        file_put_contents($this->filename, $this->resultXml);
    }
}
