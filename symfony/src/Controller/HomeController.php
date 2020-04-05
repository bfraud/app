<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Manager\LocaleManager;
use App\Manager\VolunteerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @var LocaleManager
     */
    private $locale;

    /**
     * @var VolunteerManager
     */
    private $volunteerManager;

    /**
     * HomeController constructor.
     *
     * @param LocaleManager $locale
     * @param VolunteerManager $volunteerManager
     */
    public function __construct(LocaleManager $locale, VolunteerManager $volunteerManager)
    {
        $this->locale = $locale;
        $this->volunteerManager = $volunteerManager;
    }

    /**
     * @Route(name="home")
     */
    public function home()
    {
        if (!$this->isGranted('ROLE_TRUSTED')) {
            return $this->redirectToRoute('password_login_not_trusted');
        }

        return $this->render('home.html.twig', [
            'issues' => $this->volunteerManager->findIssues(),
        ]);
    }

    /**
     * @Route("/infos/{nivol}", name="infos")
     */
    public function infos(Volunteer $volunteer)
    {
        if (!getenv('IS_REDCROSS')) {
            throw $this->createNotFoundException();
        }

        return $this->render('infos.html.twig', [
            'volunteer' => $volunteer,
        ]);
    }

    /**
     * @Route("/locale/{locale}", name="locale")
     */
    public function locale(string $locale)
    {
        $this->locale->save($locale);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/auth", name="auth")
     */
    public function auth()
    {
        return $this->redirectToRoute('home');
    }
}
