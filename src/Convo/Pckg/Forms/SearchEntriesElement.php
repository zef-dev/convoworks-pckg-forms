<?php

namespace Convo\Pckg\Forms;

use Convo\Core\Workflow\IConversationElement;
use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
use Convo\Core\Params\IServiceParamsScope;

class SearchEntriesElement extends AbstractFormsElement
{
    private $_search = [];
    private $_limit;
    private $_offset;
    private $_orderBy = [];
    private $_resultVar;

    /**
     * @var IConversationElement[]
     */
    private $_multipleFlow = array();

    /**
     * @var IConversationElement[]
     */
    private $_singleFlow = array();

    /**
     * @var IConversationElement[]
     */
    private $_emptyFlow = array();

    /**
     * @param array $properties
     */
    public function __construct( $properties)
    {
        parent::__construct( $properties);

        $this->_search   	=   $properties['search'];
        $this->_resultVar   =   $properties['result_var'];
        $this->_limit       =   $properties['limit'];
        $this->_offset      =   $properties['offset'];
        $this->_orderBy     =   $properties['order_by'];

        foreach ( $properties['multiple_flow'] as $element) {
            $this->_multipleFlow[] = $element;
            $this->addChild($element);
        }

        foreach ( $properties['single_flow'] as $element) {
            $this->_singleFlow[] = $element;
            $this->addChild($element);
        }

        foreach ( $properties['empty_flow'] as $element) {
            $this->_emptyFlow[] = $element;
            $this->addChild($element);
        }
    }


    public function read( IConvoRequest $request, IConvoResponse $response)
    {
        $context   =   $this->_getFormsContext();
        $data      =   [];
        $params    =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_REQUEST, $this);

        $search     =   $this->evaluateString( $this->_search);
        $order_by   =   $this->_sanitizeOrderBy( $this->_evaluateArgs( $this->_orderBy));
        $offset     =   $this->evaluateString( $this->_offset);
        $limit      =   $this->evaluateString( $this->_limit);
        
        $this->_logger->debug( 'Searching by ['.\print_r( $search, true).'] order['.\print_r( $order_by, true).']');
        
        $result     =   $context->searchEntries( $search, $offset, $limit, $order_by);
        
        $data['result'] = $result;
        $data['count'] = $context->getSearchCount( $search);
        
        $this->_logger->info( 'Found ['.$data['count'].'] entries');
        $this->_logger->debug( 'Got result ['.\print_r( $data['result'], true).']');

        $elements = $this->_multipleFlow;
        $this->_logger->debug( 'Default is multiple results flow');
        if ( empty( $result)) {
            if ( count( $this->_emptyFlow)) {
                $this->_logger->info( 'Using empty flow ...');
                $elements = $this->_emptyFlow;
            }
        } else if ( count( $result) === 1) {
            if ( count( $this->_singleFlow)) {
                $this->_logger->info( 'Using single result flow ...');
                $elements = $this->_singleFlow;
                $data['value'] = $result[0];
            }
        }
        
        $params->setServiceParam( $this->_resultVar, $data);
        
        foreach ( $elements as $elem) {
            $elem->read( $request, $response);
        }
    }
    
    private function _sanitizeOrderBy( $orderBy) 
    {
        $sanitized   =   [];
        foreach ( $orderBy as $key=>$val) {
            $sanitized[$key] = $this->_sanitizeOrderDirection( $val);
        }
        return $sanitized;
    }
    
    private function _sanitizeOrderDirection( $dir)
    {
        $dir = strtoupper( $dir);
        if ( $dir === 'ASC' || $dir === 'DESC') {
            return $dir;
        }
        return '';
    }
    
    // UTIL
    public function __toString()
    {
        return parent::__toString();
    }


}

