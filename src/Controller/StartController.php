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
    private LeadsProcessingInterface $leadsProcessing;

    public function __construct(LeadsProcessingInterface $leadsProcessing)
    {
        $this->leadsProcessing = $leadsProcessing;
    }

    /**
     * @param array
     */
    public function start(array $leadsArray): void
    {   
        $decryptsAndPipes = [];
        foreach ($leadsArray as $lead) {
            $decryptsAndPipes[] = $this->leadsProcessing->process($lead);
        }
        
        $this->leadsProcessing->closeAllProcess($decryptsAndPipes);
    }
}
