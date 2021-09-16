<?php

namespace App\Controller\Web;

use App\Repository\AgentRepository;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="web_index")
     */
    public function index(MissionRepository $missionRepository,AgentRepository $agentRepository): Response
    {
        $missions = $missionRepository->findByEnabled()->getQuery()->getResult();
        $agents = $agentRepository->findByPublished(true)->getQuery()->getResult();
        return $this->render('home/index.html.twig',[
            'missions' => $missions,
            'agents' => $agents
        ]);
    }
}