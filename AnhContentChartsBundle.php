<?php

namespace Anh\ContentChartsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnhContentChartsBundle extends Bundle
{
    public static function getRequiredBundles()
    {
        return array(
            'Anh\ContentBundle\AnhContentBundle',
        );
    }
}
