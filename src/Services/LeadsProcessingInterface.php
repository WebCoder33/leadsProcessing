<?php 

namespace App\Services;

use LeadGenerator\Lead;

/**
 * Interface LeadsProcessingInterface
 * @package App\Services
 */
interface LeadsProcessingInterface
{
    /**
     * @param Lead $lead
     * @return array
     */
    public function process(Lead $lead): array;
}

