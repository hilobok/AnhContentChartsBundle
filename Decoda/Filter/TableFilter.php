<?php

namespace Anh\ContentChartsBundle\Decoda\Filter;

use Decoda\Decoda;
use Decoda\Filter\TableFilter as BaseTableFilter;

class TableFilter extends BaseTableFilter
{
    protected $currentTable;
    protected $currentRow;

    public function __construct(array $config = array())
    {
        $this->_tags['table']['attributes']['name'] = self::ALNUM;
        $this->_tags['tr']['attributes']['chart'] = '/series|categories/i';

        parent::__construct($config);

        $this->resetCurrentRow();
        $this->resetCurrentTable();
    }

    public function parse(array $tag, $content)
    {
        $tagName = $this->processTagName($tag['tag']);

        $tag['attributes'] += array(
            'chart' => null,
            'name' => null,
        );

        switch ($tagName) {
            case 'td':
            case 'th':
                $this->processCol($tagName, $content);
                break;

            case 'tr':
                $this->processRow($tag['attributes']['chart']);
                break;

            case 'table':
                $this->processTable($tag['attributes']['name']);
                break;
        }

        unset($tag['attributes']['chart']);
        unset($tag['attributes']['name']);

        return parent::parse($tag, $content);
    }

    private function processCol($tag, $value)
    {
        $this->currentRow[$tag][] = ($tag == 'th')
            ? $value : $this->processValue($value)
        ;
    }

    private function processRow($type)
    {
        switch (strtolower($type)) {
            case 'categories':
                $this->currentTable['categories'] = $this->currentRow['th'];
                break;

            case 'series':
                $this->currentTable['series'][] = array(
                    'name' => reset($this->currentRow['th']) ?: 'Unnamed',
                    'data' => $this->currentRow['td'],
                );
                break;
        }

        $this->resetCurrentRow();
    }

    private function processTable($name)
    {
        $name = trim($name);

        if (strlen($name) && $this->currentTable) {
            $this->_parser->chartTables[$name] = $this->currentTable;
        }

        $this->resetCurrentTable();
        $this->resetCurrentRow();
    }

    private function processTagName($name)
    {
        $name = strtolower($name);
        $name = ($name == 'col') ? 'td' : $name;
        $name = ($name == 'row') ? 'tr' : $name;

        return $name;
    }

    private function processValue($value)
    {
        return str_replace(
            array(' ', ','),
            array('', '.'),
            $value
        ) + 0;
    }

    private function resetCurrentRow()
    {
        $this->currentRow = array(
            'td' => array(),
            'th' => array(),
        );
    }

    private function resetCurrentTable()
    {
        $this->currentTable = array();
    }
}
