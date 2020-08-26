<?php

namespace App\Services;

use LeadGenerator\Lead;

/**
 * Class LeadsProcessing
 * @package App\Services
 */
class LeadsProcessing implements LeadsProcessingInterface
{
    public const ASSYNC_SCRIPT = '/app/src/Scripts/AssyncScript.php';

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
            $command = 'php '.LeadsProcessing::ASSYNC_SCRIPT.' -f "'.$message.'"';
            $process = proc_open($command, $descriptorspec, $pipes);

            return ['process' => $process, 'pipes' => $pipes];
        } else {
            return [];
        }
    }

    /**
     * @param array
     */
    public function closeAllProcess(array $descrsiptsAndPipes): void
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

    /**
     * @param Lead
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
