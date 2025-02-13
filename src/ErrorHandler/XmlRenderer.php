<?php
namespace Yiisoft\Yii\Web\ErrorHandler;

/**
 * Formats exception into XML string
 */
final class XmlRenderer extends ThrowableRenderer
{
    public function render(\Throwable $t): string
    {
        $out = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
        $out .= "<error>\n";
        $out .= $this->tag('type', get_class($t));
        $out .= $this->tag('message', $this->cdata($t->getMessage()));
        $out .= $this->tag('code', $this->cdata($t->getCode()));
        $out .= $this->tag('file', $t->getFile());
        $out .= $this->tag('line', $t->getLine());
        $out .= $this->tag('trace', $t->getTraceAsString());
        $out .= '</error>';
        return $out;
    }

    private function tag(string $name, string $value): string
    {
        return "<$name>" . $value . "</$name>\n";
    }

    private function cdata(string $value): string
    {
        return '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $value) . ']]>';
    }
}
