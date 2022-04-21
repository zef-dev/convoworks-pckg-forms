<?php declare(strict_types=1);

namespace Convo\Pckg\Forms;



use Convo\Core\DataItemNotFoundException;

/**
 * @author Tole
 * This interface describes interaction between Convoworks workflow components and underlying forms system.
 */
interface IFormsContext
{
    
    /**
     * @param array $entry
     * @return FormValidationResult
     */
    public function validateEntry( $entry);

	/**
	 * @param array $entry 
	 * @return string entry id
	 * @throws FormValidationException:
	 */
	public function createEntry( $entry);
	
	
	/**
	 * @param string $entryId
	 * @param array $entry
	 * @throws DataItemNotFoundException
	 * @throws FormValidationException
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