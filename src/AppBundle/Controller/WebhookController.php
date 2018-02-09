<?php

namespace AppBundle\Controller;

use Mmoreram\GearmanBundle\Service\GearmanClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebhookController extends Controller
{
	private $git_repo_local_path;

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
                    /* @var GearmanClient $gearman */
                    $gearman = $this->container->get('gearman');
                    $result = $gearman
                        ->doNormalJob('AppBundleServicesGitWorkerDummy~fetchRemoteJobDummy', json_encode(array('repo' => $repo)));

                    $returnCode = $gearman->getReturnCode();
                    return new JsonResponse(array("ret" => $returnCode));
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
        $gearman = $this->container->get('gearman');
        $result = $gearman
            ->doNormalJob('AppBundleServicesGitWorkerDummy~fetchRemoteJobDummy', json_encode(array('repo' => 'sucks')));

        $returnCode = $gearman->getReturnCode();
        return new JsonResponse(array("ret" => $returnCode));
	}
}
