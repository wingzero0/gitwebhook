<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request)
	{
		// replace this example code with whatever you need
		return $this->render('default/index.html.twig', [
			'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
		]);
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
		$process = new Process('pwd');
		$process->run();

		if (!$process->isSuccessful()) {
			throw new ProcessFailedException($process);
		}

		return new JsonResponse(array("ret" => $process->getOutput()));
	}

	private function gitFetch($rep)
	{
		$process = new Process('cd ~ && ./gitfetch.sh ' . $rep );
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
			throw new ProcessFailedException($process);
		}
		return $process->getOutput();
	}
}
