<?php
namespace Convo\Pckg\Forms;

use Convo\Core\Workflow\AbstractBasicComponent;
use Convo\Core\Workflow\IServiceContext;
use Convo\Core\Params\IServiceParamsScope;
use Convo\Core\Util\StrUtil;
use Convo\Core\DataItemNotFoundException;

class DummyFormContext extends AbstractBasicComponent implements IServiceContext, IFormsContext
{
    private $_id;
    private $_requiredFields = [];

    private $_cachedEntries;
    
    public function __construct( $properties)
    {
        parent::__construct( $properties);
        
        $this->_id              =   $properties['id'];
        if ( !empty( trim( $properties['required_fields']))) {
            $this->_requiredFields  =   array_map('trim', explode( ',', $properties['required_fields']));
        }
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
        $result = new FormValidationResult();
        foreach ( $this->_requiredFields as $key) {
            if ( !isset( $entry[$key]) || trim($entry[$key]) === '') {
                $result->addError( $key, 'The field ['.$key.'] is required');
            }
        }
        return $result;
    }
    
    public function createEntry( $entry)
    {
        $this->_checkEntry( $entry);
        
        $entry['entry_id'] = StrUtil::uuidV4();
        
        $entries   =    $this->_getEntries();
        $entries[] =    $entry;
        $this->_saveEntries( $entries);
        
        return $entry['entry_id'];
    }
    
    public function deleteEntry( $entryId)
    {
        $this->getEntry( $entryId);
        
        $entries   =   $this->_getEntries();
        $entries   =   \array_filter( $entries, function ( $entry) use ( $entryId) {
            return $entry['entry_id'] !== $entryId;
        });
        $this->_saveEntries( $entries);
    }
    
    public function updateEntry( $entryId, $entry)
    {
        $existing   =   $this->getEntry( $entryId);
        
        $entry      =   array_merge( $existing, $entry, [ 'entry_id' => $entryId]);
        
        $this->_checkEntry( $entry);
        
        $entries    =   $this->_getEntries();
        $updated    =   [];
        
        foreach ( $entries as $existing) {
            if ( $existing['entry_id'] !== $entryId) {
                $updated[] = $existing;
                continue;
            }
            $updated[] = $entry;
        }
        
        $this->_saveEntries( $updated);
    }
    
    public function searchEntries( $search, $offset=0, $limit=self::DEFAULT_LIMIT, $orderBy=[])
    {
        $entries   =   $this->_getEntries();
        
        if ( empty( $search)) {
            return $entries;
        }
        
        $found  =   [];
        
        foreach ( $entries as $entry) {
            foreach ( $search as $key=>$val) {
                if ( $entry[$key] === $val) {
                    $found[] = $entry;
                    break;
                }
            }
        }
        
        return $found;
    }
    
    public function getEntry( $entryId)
    {
        $found  =   $this->searchEntries( ['entry_id' => $entryId]);
        if ( empty( $found)) {
            throw new DataItemNotFoundException( 'Entry ['.$entryId.']not found');        
        }
        return $found[0];
    }
    
    /**
     * Throw an exception if not valid
     * @param array $entry
     * @throws FormValidationException
     */
    private function _checkEntry( $entry) 
    {
        $result =   $this->validateEntry( $entry);
        if ( !$result->isValid()) {
            throw new FormValidationException( $result);
        }
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
    
    private function _saveEntries( $entries)
    {
        $params =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_USER, $this);
        $params->setServiceParam( 'forms', $entries);
        $this->_cachedEntries  =   $entries;
    }

	// UTIL
	public function __toString()
	{
	    return parent::__toString().'['.$this->_id.']';
	}


}