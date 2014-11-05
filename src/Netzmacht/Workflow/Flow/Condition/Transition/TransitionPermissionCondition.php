<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Workflow\Flow\Condition\Transition;


use Netzmacht\Workflow\Acl\Role;
use Netzmacht\Workflow\Flow\Context;
use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\Transition;

/**
 * Class TransitionPermissionCondition limit permission of a transition to the given transition roles.
 *
 * @package Netzmacht\Workflow\Flow\Condition\Transition
 */
class TransitionPermissionCondition extends AbstractPermissionCondition
{
    /**
     * Consider if condition matches for the given entity.
     *
     * @param Transition $transition The transition being in.
     * @param Item       $item       The entity being transits.
     * @param Context    $context    The transition context.
     *
     * @return bool
     */
    public function match(Transition $transition, Item $item, Context $context)
    {
        if ($this->isGranted($transition->getRoles())) {
            return true;
        }

        return false;
    }

    /**
     * Describes an failed condition.
     *
     * It returns an array with 2 parameters. First one is the error message code. The second one are the params to
     * be replaced in the message.
     *
     * Example return array('transition.condition.example', array('name', 'value'));
     *
     * @param Transition $transition The transition being in.
     * @param Item       $item       The entity being transits.
     * @param Context    $context    The transition context.
     *
     * @return array
     */
    public function describeError(Transition $transition, Item $item, Context $context)
    {
        return array(
            'transition.condition.transition-permission',
            array(
                array(
                    $this->describeRoles($transition->getRoles())
                )
            )
        );
    }


    /**
     * Convert roles to an readable string.
     *
     * @param Role[] $roles Permission roles.
     *
     * @return string
     */
    private function describeRoles($roles)
    {
        $roles = array_map(
            function (Role $role) {
                return $role->getName();
            },
            $roles
        );

        return '[' . implode(', ', $roles) . ']';
    }
}