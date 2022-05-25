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
        
        $entries   =    $this->_getEntries();
        $entry_id  =    StrUtil::uuidV4();
        $entries[] =    [
            'entry_id' => $entry_id,
            'time_created' => time(),
            'meta_values' => $entry
        ];
        
        $this->_saveEntries( $entries);
        
        return $entry_id;
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
        $existing['meta_values'] = array_merge( $existing['meta_values'], $entry);
        $this->_checkEntry( $existing['meta_values']);
        
        $entries    =   $this->_getEntries();
        $updated    =   [];
        
        foreach ( $entries as $en) 
        {
            if ( $en['entry_id'] === $entryId) {
                $existing['time_updated'] = time();
                $updated[] = $existing;
                $this->_logger->info( 'Updating entry ['.$en['entry_id'].']');
                continue;
            }
            $updated[] = $en;
        }
        
        $this->_saveEntries( $updated);
    }
    
    public function searchEntries( $search, $offset=0, $limit=self::DEFAULT_LIMIT, $orderBy=[])
    {
        $found  =   $this->_performSearch( $search);
        $found  =   \array_slice( $found, $offset, $limit);
        
        if ( !empty( $orderBy)) 
        {
            \usort( $found, function ( $a, $b) use ($orderBy) {
                foreach ( $orderBy as $key=>$val)
                {
                    $ret = \strcmp( $a[$key], $b[$key]);
                    if ( $ret !== 0) {
                        return $ret * ($val==='DESC' ? -1 : 1);
                    }
                }
                return 0;
            });
        }
        
        return $found;
    }
    
    private function _performSearch( $search) 
    {
        $this->_logger->debug( 'Searching for ['.print_r( $search, true).']');
        $entries   =   $this->_getEntries();
        
        if ( empty( $search)) {
            return $entries;
        }
        
        $found  =   [];
        
        foreach ( $entries as $entry) 
		{
            if ( $this->_isEntryMatch( $entry, $search)) {
                $found[] = $entry;
            }
        }
        return $found;
    }
    
    private function _isEntryMatch( $entry, $search) {
        foreach ( $search as $key=>$val) {
            if ( isset( $entry[$key]) && ($entry[$key] === $val)) {
                return true;
            }
            if ( isset( $entry['meta_values'][$key]) && ($entry['meta_values'][$key] === $val)) {
                return true;
            }
        }
        return false;
    }
    
    public function getSearchCount( $search) 
    {
        $found  =   $this->_performSearch( $search);
        return count( $found);
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
            $params                 =   $this->getService()->getComponentParams( IServiceParamsScope::SCOPE_TYPE_USER, $this);
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