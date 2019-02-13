<?php

namespace GunWiki\SiteInfo\LogModel;

class ViewParser
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var View
     */
    private $result;

    public function __construct(string $text, string $pattern)
    {
        $this->text = $text;
        $this->pattern = $pattern;
        $this->parse();
    }

    private function parse()
    {
        if (!preg_match($this->pattern, $this->text, $matches)) {
            throw new \RuntimeException("Failed to parse: {$this->text}");
        }
        $result = new View;
        $result->setTime(strtotime($matches['time']));
        $result->setHttpCode($matches['statusCode']);
        $result->setIp($matches['IP']);
        $result->setHttpMethod($matches['method']);
        $result->setSize($matches['size']);
        $result->setURL($matches['URL']);
        $result->setRefer($matches['refer']);
        $result->setRefer($matches['refer'] ?? null);
        $result->setUA($matches['UA'] ?? null);
        $this->result = $result;
    }

    public function getResult() : View
    {
        return $this->result;
    }
}
