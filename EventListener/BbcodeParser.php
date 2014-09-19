<?php

namespace Anh\ContentChartsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Anh\MarkupBundle\Event\MarkupEvent;
use Anh\MarkupBundle\Event\MarkupCreateEvent;
use Anh\ContentChartsBundle\Decoda\Filter\TableFilter;
use Anh\ContentChartsBundle\Decoda\Filter\ChartFilter;
use Anh\ContentChartsBundle\Decoda\Hook\ChartHook;

class BbcodeParser implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            MarkupEvent::CREATE => 'onCreate',
        );
    }

    /**
     * Injects table and chart filters into bbcode markup parser
     *
     * @param MarkupCreateEvenet $event
     */
    public function onCreate(MarkupCreateEvent $event)
    {
        if ($event->getType() != 'bbcode') {
            return;
        }

        $decoda = $event->getParser();

        if (!$decoda) {
            throw new \Exception(
                'AnhContentBundle should be initialized before AnhContentChartsBundle.'
            );
        }

        $decoda->addFilter(new TableFilter());
        $decoda->addFilter(new ChartFilter());
        $decoda->addHook(new ChartHook());

        $event->setParser($decoda);
    }
}
