<?php

namespace AppBundle\Services;

use Mmoreram\GearmanBundle\Driver\Gearman;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
    private $git_repo_local_path;

    public function __construct($git_repo_local_path)
    {
        $this->git_repo_local_path = $git_repo_local_path;
    }

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
        $this->git_repo_local_path = '/home/webmaster/git';
        echo $data['repo'] . "\n" . $this->git_repo_local_path . "\n" . PHP_EOL;

        return true;
    }

    private function gitFetch($rep)
    {
        $process = new Process('cd ' . $this->git_repo_local_path . ' && ./gitfetch.sh ' . $rep );
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }
}
