<?php

namespace Anh\ContentChartsBundle\Decoda\Filter;

use Decoda\Filter\AbstractFilter;

class ChartFilter extends AbstractFilter
{
    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = array(
        'chart' => array(
            'htmlTag' => 'figure',
            'attributes' => array(
                'id' => self::ALNUM,
                'class' => self::ALNUM,
                'from' => '/.+?(|[\d,]+)?/',
            ),
            'htmlAttributes' => array(
                'class' => 'decoda-chart',
            ),
        ),
    );

    public function parse(array $tag, $content)
    {
        $chart = array();
        $fromTable = isset($tag['attributes']['from']) ? trim($tag['attributes']['from']) : '';

        if ($fromTable) {
            list($tableName, $seriesKeys) = explode('|', $fromTable) + array(null, null);

            if (!isset($this->_parser->chartTables[$tableName])) {
                if (isset($tag['isPostponed']) && $tag['isPostponed']) {
                    return sprintf(
                        '<div class="decoda-error">Chart error: table \'%s\' not found.</div>',
                        $tableName
                    );
                }

                $tag['isPostponed'] = true;

                return sprintf('<!-- ###chart{%s}### -->', base64_encode(serialize(array(
                    'tag' => $tag,
                    'content' => $content,
                ))));
            }

            $table = $this->_parser->chartTables[$tableName];

            $categories = isset($table['categories']) ? $table['categories'] : array();
            $series = isset($table['series']) ? $table['series'] : array();

            if (!empty($seriesKeys)) {
                $series = array();

                foreach (explode(',', $seriesKeys) as $key) {
                    if (isset($table['series'][$key])) {
                        $series[] = $table['series'][$key];
                    }
                }
            }

            $chart = array(
                'xAxis' => array(
                    'categories' => $categories,
                ),
                'series' => $series,
            );
        }

        $data = (array) json_decode($content, true);

        if (!$data && strlen(trim($content)) > 0) {
            return sprintf(
                '<div class="decoda-error">Chart error: unable to parse chart options.</div>'
            );
        }

        $tag['attributes']['data-chart'] = json_encode(
            $this->merge($chart, $data)
        );

        return parent::parse($tag, '');
    }

    protected function merge(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->merge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
