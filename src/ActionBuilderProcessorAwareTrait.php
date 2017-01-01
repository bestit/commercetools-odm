<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;

/**
 * Helps providing the action builder.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
trait ActionBuilderProcessorAwareTrait
{
    /**
     * The processor to build update actions.
     * @var ActionBuilderProcessorInterface
     */
    private $actionBuilderProcessor = null;

    /**
     * Returns the processor to build update actions.
     * @return ActionBuilderProcessorInterface
     */
    public function getActionBuilderProcessor(): ActionBuilderProcessorInterface
    {
        return $this->actionBuilderProcessor;
    }

    /**
     * Sets the processor to build update actions.
     * @param ActionBuilderProcessorInterface $actionBuilderProcessor
     * @return $this
     */
    protected function setActionBuilderProcessor(ActionBuilderProcessorInterface $actionBuilderProcessor)
    {
        $this->actionBuilderProcessor = $actionBuilderProcessor;

        return $this;
    }
}
