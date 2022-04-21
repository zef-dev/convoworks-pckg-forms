<?php
namespace Convo\Pckg\Forms;

use Convo\Core\Workflow\AbstractBasicComponent;
use Convo\Core\Workflow\IServiceContext;
use Convo\Core\Params\IServiceParamsScope;

class DummyFormContext extends AbstractBasicComponent implements IServiceContext, IFormsContext
{
    private $_id;

    private $_cachedEntries;
    
    public function __construct( $properties)
    {
        parent::__construct( $properties);
        
        $this->_id = $properties['id'];
    }
	
    
    /**
     * @return mixed
     */
    public function init()
    {
        $this->_logger->info( 'Initializing ['.$this.']');
    }
    
    /**
     * @return mixed
     */
    public function getComponent()
    {
        return $this;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    // FORMS
    public function validateEntry( $entry) 
    {
        return new FormValidationResult();
    }
    
    public function searchEntries($search)
    {
        return [];
    }
    
    public function createEntry( $entry)
    {
        return '';
    }
    
    public function deleteEntry( $entryId)
    {}
    
    public function updateEntry( $entryId, $entry)
    {}
    
    public function getEntry( $entryId)
    {
        return [];
    }
    
    // DATA
    private function _getEntries()
    {
        if ( !isset( $this->_cachedEntries)) {
            $params                      =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_USER, $this);
            $this->_cachedEntries   =   $params->getServiceParam( 'forms');
            if ( is_null( $this->_cachedEntries)) {
                $this->_cachedEntries   =    [];
            }
        }
        return $this->_cachedEntries;
    }
    
    private function _saveEntries( $appointments)
    {
        $params =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_USER, $this);
        $params->setServiceParam( 'forms', $appointments);
        $this->_cachedEntries  =   $appointments;
    }

	// UTIL
	public function __toString()
	{
	    return parent::__toString().'['.$this->_id.']';
	}


}