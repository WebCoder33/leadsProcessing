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
        
        $this->closeAllProcess($descrsiptsAndPipes);
    }

    /**
     * @param array
     */
    private function closeAllProcess(array $descrsiptsAndPipes): void
    {
        foreach ($descrsiptsAndPipes as $key) {

            $pipes = $key['pipes'];
            $process = $key['process'];
            $meta_info = proc_get_status($process);

            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                fclose($pipe);
                }
            }
            
            $exit_code = proc_close($process);
            $exit_code = $meta_info['running'] ? $exit_code : $meta_info['exitcode'];
        }
    }
}