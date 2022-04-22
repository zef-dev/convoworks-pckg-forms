<?php

namespace Convo\Pckg\Forms;

use Convo\Core\DataItemNotFoundException;
use Convo\Core\Workflow\IConversationElement;
use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
use Convo\Core\Params\IServiceParamsScope;

class LoadEntryElement extends AbstractFormsElement
{
    private $_entry_id;
    private $_resultVar;

    /**
     * @var IConversationElement[]
     */
    private $_ok = array();

    /**
     * @param array $properties
     */
    public function __construct( $properties)
    {
        parent::__construct( $properties);

        $this->_entry_id   	  =   $properties['entry_id'];
        $this->_resultVar     =   $properties['result_var'];

        foreach ( $properties['ok'] as $element) {
            $this->_ok[] = $element;
            $this->addChild($element);
        }
    }


    public function read( IConvoRequest $request, IConvoResponse $response)
    {
        $context   =   $this->_getFormsContext();
        $entry_id  =   $this->evaluateString($this->_entry_id);
        $params    =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_REQUEST, $this);

        $this->_logger->info('Loading entry with id [' . $entry_id . ']');

        try {
            $data = ['form_id' => null];
            $data['form_id'] = $context->getEntry($entry_id);
            $this->_logger->info('Loaded entry with id [' . $entry_id . ']');
        } catch ( DataItemNotFoundException $e) {
            $this->_logger->info( $e->getMessage());
            $data['message']   =   $e->getMessage();
        }

        $params->setServiceParam( $this->_resultVar, $data);

        foreach ( $this->_ok as $elem) {
            $elem->read( $request, $response);
        }
    }

    // UTIL
    public function __toString()
    {
        return parent::__toString();
    }


}

