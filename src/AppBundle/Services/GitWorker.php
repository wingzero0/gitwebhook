<?php

namespace AppBundle\Services;

use Mmoreram\GearmanBundle\Driver\Gearman;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Gearman\Work(
 *     name = "GitWorkerDummy",
 *     iterations = 3,
 *     description = "Worker handle git sync repo from remote server",
 *     defaultMethod = "doBackground",
 * )
 */
class GitWorker implements ContainerAwareInterface
{
    private $git_repo_local_path;
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
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
        //$this->git_repo_local_path = '/home/webmaster/git';
        $this->git_repo_local_path = $this->container->getParameter('git_repo_local_path');
        echo $data['repo'] . "\n" . $this->git_repo_local_path . "\n" . PHP_EOL;
        $processOuput = $this->gitFetch($data['repo']);
        print_r($processOuput);

        return true;
    }

    private function gitFetch($rep)
    {
        $process = new Process('cd ' . $this->git_repo_local_path . ' && ./gitfetch.sh ' . $rep . '.git' );
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }
}
