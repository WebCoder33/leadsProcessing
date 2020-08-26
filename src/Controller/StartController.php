<?php

namespace App\Controller;

use App\Services\LeadsProcessingInterface;

/**
 * Class StartController
 * @package App\Controller
 */
class StartController
{
    /**
     * @param LeadsProcessingInterface
     */
    private $leadsProcessing;

    public function __construct(LeadsProcessingInterface $leadsProcessing)
    {
        $this->leadsProcessing = $leadsProcessing;
    }

    /**
     * @param array
     */
    public function start(array $leadsArray): void
    {   
        $descrsiptsAndPipes = [];
        foreach ($leadsArray as $lead) {
            $descrsiptsAndPipes[] = $this->leadsProcessing->process($lead);
        }
        
        $this->leadsProcessing->closeAllProcess($descrsiptsAndPipes);
    }
}
