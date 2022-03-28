<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\Security\LoginType;
use App\Form\Security\RegisterType;
use App\Form\Security\RequestType;
use App\Form\Security\ResetType;
use App\Manager\TokenManager;
use App\Manager\UserManager;
use App\Services\MessageService;
use App\Services\TokenService;
use App\Services\TranslatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin_74ze5f/login", name="admin_login", methods={"GET","POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param MessageService $messageService
     * @param UserManager $userManager
     * @param TokenManager $tokenManager
     * @param TranslatorService $translatorService
     * @return Response
     * @throws \Exception
     */
    public function admin(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        MessageService $messageService,
        UserManager $userManager,
        TokenManager $tokenManager,
        TranslatorService $translatorService
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, [
            '_username' => $authenticationUtils->getLastUsername(),
            'csrf_protection' => false,
        ]);

        $formRequestPassword = $this->createForm(RequestType::class, null);
        $formRequestPassword->handleRequest($request);

        if ($formRequestPassword->isSubmitted() && $formRequestPassword->isValid()) {
            $user = $userManager->loadByUsername($formRequestPassword->getData()['username']);

            if ($user instanceof User) {
                $tokenManager->create($user);
                $messageService->addSuccess($translatorService->translate('message.flash.resetpasswordsave'));
            } else {
                $messageService->addError($translatorService->translate('message.flash.resetpasswordnosave'));
            }
        }

        $parameters = [
            'error' => $error,
            'form' => $form->createView(),
            'admin' => 'Admin',
            'formRequestPassword' => $formRequestPassword->createView()
        ];

        return $this->render('security/login.html.twig', $parameters);
    }

    /**
     * @Route("/user/login", name="login", methods={"GET","POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function login(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        UserManager $userManager
    ): Response {
        if ($this->getUser()) {
            return $userManager->redirectionAuth($this->getUser());
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $form = $this->createForm(LoginType::class, [
            '_username' => $authenticationUtils->getLastUsername(),
        ]);

        $formRequestPassword = $this->createForm(RequestType::class, null);
        $formRequestPassword->handleRequest($request);

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/register", name="register", methods={"GET","POST"})
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserManager $userManager): Response
    {
        if ($this->getUser()) {
            $userManager->redirectionAuth($this->getUser());
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->registerAccount($user);

            return $this->redirectToRoute('register');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     * @param MessageService $messageService
     * @param UserManager $userManager
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activation(
        MessageService $messageService,
        UserManager $userManager,
        string $token
    ) {
        $user = $userManager->checkToken($token);

        if ($user instanceof User) {
            $userManager->activeUser($user);
        }

        $messageService->addSuccess('Cet token d\'activation est valide.');

        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/user/password/reset/{reference}", name="user_reset", methods={"GET","POST"})
     * @param string $reference
     * @param Request $request
     * @param MessageService $messageService
     * @param TokenService $tokenService
     * @param UserManager $userManager
     * @param TranslatorService $translatorService
     * @return Response
     * @throws \Exception
     */
    public function reset(
        string $reference,
        Request $request,
        MessageService $messageService,
        TokenService $tokenService,
        UserManager $userManager,
        TranslatorService $translatorService
    ): Response {
        if (!$tokenService->isValid($reference)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $userManager->setPassword($reference, $data['password']);
            $messageService->addSuccess($translatorService->translate('message.flash.resetpassword'));

            return $this->redirectToRoute('login');
        }

        return $this->render('security/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin_74ze5f/logout", name="admin_logout", methods={"GET"})
     * @Route("/user/logout", name="user_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}