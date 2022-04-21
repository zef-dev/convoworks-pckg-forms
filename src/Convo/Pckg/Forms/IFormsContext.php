<?php declare(strict_types=1);

namespace Convo\Pckg\Forms;



/**
 * @author Tole
 * This interface describes interaction between Convoworks workflow components and underlying forms system.
 */
interface IFormsContext
{
    const DEFAULT_ENTRIES_COUNT    =   10;
    
    
    /**
     * @param array $entry
     * @return FormValidationResult
     */
    public function validateEntry( $entry);

	/**
	 * @param array $entry 
	 * @return string entry id
	 */
	public function createEntry( $entry);
	
	
	/**
	 * @param string $entryId
	 * @param array $entry
	 * @throws DataItemNotFoundException:
	 */
	public function updateEntry( $entryId, $entry);
	
	/**
	 * @param string $entryId
	 * @throws DataItemNotFoundException:
	 */
	public function deleteEntry( $entryId);
	
	/**
	 * @param string $entryId
	 * @throws DataItemNotFoundException:
	 */
	public function getEntry( $entryId);
	
	
	/**
	 * @param array $search
	 */
	public function searchEntries( $search);
	
	
	
	
	
}