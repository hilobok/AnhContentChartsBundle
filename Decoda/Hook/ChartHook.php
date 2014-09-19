<?php

namespace Anh\ContentChartsBundle\Decoda\Hook;

use Decoda\Hook\AbstractHook;

class ChartHook extends AbstractHook
{
    /**
     * Parse postponed charts
     *
     * @param  string $content
     * @return string
     */
    public function afterParse($content)
    {
        return preg_replace_callback('/\<\!\-\- ###chart\{(.+?)\}### \-\-\>/', function ($matches) {
            $data = unserialize(base64_decode($matches[1]));

            return $this->_parser->getFilter('Chart')
                ->parse($data['tag'], $data['content'])
            ;
        }, $content);
    }
}
