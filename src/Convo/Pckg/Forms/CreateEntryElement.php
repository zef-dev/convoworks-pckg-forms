<?php

namespace Convo\Pckg\Forms;

use Convo\Core\Workflow\IConversationElement;
use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
use Convo\Core\Params\IServiceParamsScope;

class CreateEntryElement extends AbstractFormsElement
{

    private $_entry = [];
    private $_resultVar;

    /**
     * @var IConversationElement[]
     */
    private $_ok = array();

    /**
     * @var IConversationElement[]
     */
    private $_validationError = array();

	/**
	 * @param array $properties
	 */
	public function __construct( $properties)
	{
		parent::__construct( $properties);

		$this->_entry         =   $properties['entry'];
		$this->_resultVar     =   $properties['result_var'];

		foreach ( $properties['ok'] as $element) {
		    $this->_ok[] = $element;
		    $this->addChild($element);
		}

		foreach ( $properties['validation_error'] as $element) {
		    $this->_validationError[] = $element;
		    $this->addChild($element);
		}
	}


	public function read( IConvoRequest $request, IConvoResponse $response)
	{
	    $context   =   $this->_getFormsContext();
	    $data      =   [];
	    $params    =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_REQUEST, $this);

	    $entry     =   $this->getService()->evaluateArgs( $this->_entry, $this);

	    $this->_logger->info( 'Creating entry ['.json_encode( $entry).']');

	    try {
	        $entry_id          =   $context->createEntry( $entry);
	        $this->_logger->info( 'Entry created with entry id ['.$entry_id.']');
	        $data['entry_id']  =   $entry_id;
	        $elements          =   $this->_ok;
	    } catch ( FormValidationException $e) {
	        $this->_logger->info(  $e->getMessage());
	        $data['message']   =   $e->getMessage();
	        $data['errors']    =   $e->getResult()->getErrors();
	        $elements          =   $this->_validationError;
	    }

	    $params->setServiceParam( $this->_resultVar, $data);

	    foreach ( $elements as $elem) {
	        $elem->read( $request, $response);
	    }
	}

	// UTIL
	public function __toString()
	{
	    return parent::__toString().'['.$this->_resultVar.']';
	}


}
