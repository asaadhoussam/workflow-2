<?php

namespace spec\Netzmacht\Workflow\Handler\Event;

use Netzmacht\Workflow\Flow\Context;
use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\Workflow;
use Netzmacht\Workflow\Handler\Event\PreTransitionEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BuildFormEventSpec
 * @package spec\Netzmacht\Workflow\Handler\Event
 * @mixin PreTransitionEvent
 */
class PreTransitionEventSpec extends ObjectBehavior
{
    const TRANSITION_NAME = 'transition_name';

    function let(Workflow $workflow, Item $item, Context $context)
    {
        $this->beConstructedWith($workflow, $item, $context, static::TRANSITION_NAME);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\Workflow\Handler\Event\PreTransitionEvent');
    }

    function it_extends_abstract_flow_event()
    {
        $this->shouldHaveType('Netzmacht\Workflow\Handler\Event\AbstractFlowEvent');
    }

    function it_gets_the_transition_name()
    {
        $this->getTransitionName()->shouldReturn(static::TRANSITION_NAME);
    }
}
