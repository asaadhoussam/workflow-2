<?php

/**
 * Workflow library.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus
 * @license    LGPL 3.0 https://github.com/netzmacht/workflow
 * @filesource
 */

namespace Netzmacht\Workflow\Flow;

use DateTime;
use Netzmacht\Workflow\Data\EntityId;
use Netzmacht\Workflow\Data\ErrorCollection;

/**
 * Class State stores information of a current state of an entity.
 *
 * @package Netzmacht\Workflow\Flow
 */
class State
{
    /**
     * The state id.
     *
     * @var int
     */
    private $stateId;

    /**
     * The entity id.
     *
     * @var EntityId
     */
    private $entityId;

    /**
     * Store if transition was successful.
     *
     * @var bool
     */
    private $successful;

    /**
     * The last transition.
     *
     * @var string
     */
    private $transitionName;

    /**
     * The current step.
     *
     * @var string
     */
    private $stepName;

    /**
     * Date being stored.
     *
     * @var array
     */
    private $data = array();

    /**
     * Date when state was reached.
     *
     * @var DateTime
     */
    private $reachedAt;

    /**
     * List of errors.
     *
     * @var array
     */
    private $errors;

    /**
     * Name of the workflow.
     *
     * @var string
     */
    private $workflowName;

    /**
     * Construct.
     *
     * @param EntityId $entityId       The entity id.
     * @param string   $workflowName   Workflow name.
     * @param string   $transitionName The transition executed to reach the step.
     * @param string   $stepToName     The step reached after transition.
     * @param bool     $successful     Consider if transition was successful.
     * @param array    $data           Stored data.
     * @param DateTime $reachedAt      Time when state was reached.
     * @param array    $errors         List of errors.
     * @param int      $stateId        The state id of a persisted state.
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        EntityId $entityId,
        $workflowName,
        $transitionName,
        $stepToName,
        $successful,
        array $data,
        DateTime $reachedAt,
        array $errors = array(),
        $stateId = null
    ) {
        $this->entityId       = $entityId;
        $this->workflowName   = $workflowName;
        $this->transitionName = $transitionName;
        $this->stepName       = $stepToName;
        $this->successful     = $successful;
        $this->data           = $data;
        $this->reachedAt      = $reachedAt;
        $this->errors         = $errors;
        $this->stateId        = $stateId;
    }

    /**
     * Create an initial state.
     *
     * @param EntityId        $entityId        The entity id.
     * @param Transition      $transition      The current executed transition.
     * @param Context         $context         The context.
     * @param ErrorCollection $errorCollection The error collection.
     * @param bool            $success         Success state.
     *
     * @return State
     */
    public static function start(
        EntityId $entityId,
        Transition $transition,
        Context $context,
        ErrorCollection $errorCollection,
        $success
    ) {
        $state = new State(
            $entityId,
            $transition->getWorkflow()->getName(),
            $transition->getName(),
            $transition->getStepTo()->getName(),
            $success,
            $context->getProperties(),
            new \DateTime(),
            $errorCollection->toArray()
        );

        return $state;
    }

    /**
     * Get step name.
     *
     * @return string
     */
    public function getStepName()
    {
        return $this->stepName;
    }

    /**
     * Get transition name.
     *
     * @return string
     */
    public function getTransitionName()
    {
        return $this->transitionName;
    }

    /**
     * Get the workflow name.
     *
     * @return string
     */
    public function getWorkflowName()
    {
        return $this->workflowName;
    }

    /**
     * Get state data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get reached at time.
     *
     * @return DateTime
     */
    public function getReachedAt()
    {
        return $this->reachedAt;
    }

    /**
     * Consider if state is successful.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Get the entity id.
     *
     * @return EntityId
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Get error messages.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get state id.
     *
     * @return int
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * Transit to a new state.
     *
     * @param Transition      $transition      The transition being performed.
     * @param Context         $context         The transition context.
     * @param ErrorCollection $errorCollection The error collection.
     * @param bool            $success         The success state.
     *
     * @return State
     */
    public function transit(Transition $transition, Context $context, ErrorCollection $errorCollection, $success = true)
    {
        $dateTime = new DateTime();
        $stepName = $success ? $transition->getStepTo()->getName() : $this->stepName;

        return new static(
            $this->entityId,
            $this->workflowName,
            $transition->getName(),
            $stepName,
            $success,
            $context->getProperties(),
            $dateTime,
            $errorCollection->getErrors()
        );
    }
}
