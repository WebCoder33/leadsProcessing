<?php

namespace App\Services;

use LeadGenerator\Lead;

/**
 * Class LeadsProcessing
 * @package App\Services
 */
class LeadsProcessing implements LeadsProcessingInterface
{
    public const ASYNC_SCRIPT = '/app/src/Scripts/AsyncScript.php';

    /**
     * @param Lead $lead
     * @return array
     */
    public function process(Lead $lead): array
    {   
        $message = $this->getMessage($lead);
        
        if ($message !== '') {
            
            $descriptorspec = array(
                0 => array('pipe', 'r'),
                1 => array('pipe', 'w')
            );

            $pipes = [];
            $command = 'php '.LeadsProcessing::ASYNC_SCRIPT.' -f "'.$message.'"';
            $process = proc_open($command, $descriptorspec, $pipes);

            return ['process' => $process, 'pipes' => $pipes];
        } else {
            return [];
        }
    }

    /**
     * @param array $decryptsAndPipes
     * @return array
     */
    public function closeAllProcess(array $decryptsAndPipes): array
    {
        $exitCodes = [];
        foreach ($decryptsAndPipes as $key) {

            $pipes = $key['pipes'];
            $process = $key['process'];
            $metaInfo = proc_get_status($process);

            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                fclose($pipe);
                }
            }

            $exitCode = proc_close($process);
            $exitCode = $metaInfo['running'] ? $exitCode : $metaInfo['exitcode'];

            $exitCodes[] = $exitCode;
        }

        return $exitCodes;
    }

    /**
     * @param Lead $lead
     * @return string
     */
    private function getMessage(Lead $lead): string
    {
        try {
            $leadId = $lead->id;
            $categoryName = $lead->categoryName;

            return 'lead_id: '.$leadId.'; '.
                'lead_category: '.$categoryName.'; '.
                'current_datetime: '.(new \DateTime('NOW', new \DateTimeZone('Europe/Moscow')))->format(\DateTime::ATOM);
        } catch (\Exception|\Error $e) {
            return '';
        }
    }
}
