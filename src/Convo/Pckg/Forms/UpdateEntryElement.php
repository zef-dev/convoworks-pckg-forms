<?php

namespace Convo\Pckg\Forms;

use Convo\Core\DataItemNotFoundException;
use Convo\Core\Workflow\IConversationElement;
use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
use Convo\Core\Params\IServiceParamsScope;

class UpdateEntryElement extends AbstractFormsElement
{
    private $_entry_id;
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

        $this->_entry_id   	  =   $properties['entry_id'];
        $this->_entry   	  =   $properties['entry'];
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
        $entry_id  =   $this->evaluateString($this->_entry_id);
        $data      =   [];
        $params    =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_REQUEST, $this);

        $this->_logger->info('Updating entry with id [' . $entry_id . ']');

        $entry     =   $this->_evaluateArgs( $this->_entry);

        try {
            $data               =   ['existing'  =>  $context->getEntry($entry_id)];
            $context->updateEntry($entry_id,$entry);
            $elements           =   $this->_ok;
        } catch ( FormValidationException $e) {
            $this->_logger->info( $e->getMessage());
            $data['message']    =   $e->getMessage();
            $data['errors']     =   $e->getResult()->getErrors();
            $elements           =   $this->_validationError;
        }

        $data               =   ['updated'  =>  $context->getEntry($entry_id)];

        $this->_logger->info('Updated entry with id [' . $entry_id . ']');

        $params->setServiceParam( $this->_resultVar, $data);

        foreach ( $elements as $elem) {
            $elem->read( $request, $response);
        }
    }

    // UTIL
    public function __toString()
    {
        return parent::__toString();
    }


}
