<?php

namespace AppBundle\Services;

use Mmoreram\GearmanBundle\Driver\Gearman;

/**
 * @Gearman\Work(
 *     name = "GitWorkerDummy",
 *     iterations = 3,
 *     description = "Worker handle git sync repo from remote server",
 *     defaultMethod = "doBackground",
 * )
 */
class GitWorker
{

    /**
     * Test method to run as a job
     *
     * @param \GearmanJob $job Object with job parameters
     *
     * @return boolean
     *
     * @Gearman\Job(
     *     name = "fetchRemoteJobDummy",
     *     description = "This is a description"
     * )
     */
    public function fetchRemote(\GearmanJob $job)
    {
        $data = json_decode($job->workload(),true);
        echo $data['repo'] . PHP_EOL;

        return true;
    }
}
