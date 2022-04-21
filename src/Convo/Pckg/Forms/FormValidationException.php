<?php declare(strict_types=1);

namespace Convo\Pckg\Forms;

class FormValidationException extends \Exception
{
    /**
     * @var FormValidationResult
     */
    private $_result;
    
    /**
     * @param FormValidationResult $result
     */
    public function __construct( $result) {
        $this->_result = $result;
    }
    
    public function getResult() 
    {
        return $this->_result;
    }
    
    public function getMessage()
    {
        return $this->_result->getMessage();
    }
}