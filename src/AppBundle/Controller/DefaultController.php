<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
					return new JsonResponse(array(
						"name" => $params["repository"]["name"],
						"crawling" => $params["repository"]["links"]["html"]
					));
				}
			}
		}
		return new JsonResponse(array("ret" => "parse error"));
	}
}
