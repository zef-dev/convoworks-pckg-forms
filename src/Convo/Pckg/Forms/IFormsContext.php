<?php declare(strict_types=1);

namespace Convo\Pckg\Forms;



use Convo\Core\DataItemNotFoundException;

/**
 * @author Tole
 * This interface describes interaction between Convoworks workflow components and underlying forms system.
 */
interface IFormsContext
{
    const DEFAULT_LIMIT    =   3;
    
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
	 * @param int $offset
	 * @param int $limit
	 * @param array $orderBy
	 * @return array of entries
	 */
	public function searchEntries( $search, $offset=0, $limit=self::DEFAULT_LIMIT, $orderBy=[]);
	
	/**
	 * @param array $search
	 * @return int
	 */
	public function getSearchCount( $search);
	
	
}