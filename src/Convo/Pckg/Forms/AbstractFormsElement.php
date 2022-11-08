<?php

namespace Convo\Pckg\Forms;

use Convo\Core\Workflow\AbstractWorkflowContainerComponent;
use Convo\Core\Workflow\IConversationElement;

abstract class AbstractFormsElement extends AbstractWorkflowContainerComponent implements IConversationElement
{
	/**
	 * @var string
	 */
	protected $_contextId;

	/**
	 * @param array $properties
	 */
	public function __construct( $properties)
	{
		parent::__construct( $properties);

		$this->_contextId         =   $properties['context_id'];
	}
	

	/**
	 * @return IFormsContext
	 */
	protected function _getFormsContext()
	{
		return $this->getService()->findContext(
			$this->evaluateString( $this->_contextId),
		    IFormsContext::class);
	}

	// UTIL
	public function __toString()
	{
	    return parent::__toString().'['.$this->_contextId.']';
	}
}