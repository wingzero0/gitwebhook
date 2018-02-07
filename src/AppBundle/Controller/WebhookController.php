<?php

namespace AppBundle\Controller;

use Mmoreram\GearmanBundle\Service\GearmanClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WebhookController extends Controller
{
	private $git_repo_local_path;
	public function __construct($git_repo_local_path)
	{
		$this->git_repo_local_path = $git_repo_local_path;
	}
	/**
	 * @Route("/gitwebhook", name="gitwebhook")
	 */
	public function webhookAction(Request $request)
	{
		$content = $request->getContent();
		$params = json_decode($content, true);
		if (isset($params["repository"])) {
			if (isset($params["repository"]["name"]) && isset($params["repository"]["links"])){
				if (isset($params["repository"]["links"]["html"])){
					$repo = $params["repository"]["name"];
					$link = $params["repository"]["links"]["html"];
					$ret = $this->gitFetch($repo);
		      return new JsonResponse(array("ret" => $ret));
				}
			}
		}
		return new JsonResponse(array("ret" => "parse error"));
	}
	/**
	 * @Route("/shell", name="shellCommand")
	 */
	public function shellAction(Request $request)
	{
	    /* @var GearmanClient $gearman */
	    $gearman = $this->container->get('gearman');
        $result = $gearman
            ->doNormalJob('AppBundleServicesGitWorkerDummy~fetchRemoteJobDummy', json_encode(array('repo' => 'sucks')));

        $returnCode = $gearman->getReturnCode();
        return new JsonResponse(array("ret" => $returnCode));
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
