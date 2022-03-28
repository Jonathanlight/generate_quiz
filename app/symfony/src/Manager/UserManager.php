<?php

namespace App\Manager;

use App\Entity\User;
use App\Services\MessageService;
use App\Services\PasswordService;
use App\Services\TranslatorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $userRepository;

    /**
     * @var PasswordService
     */
    protected $passwordService;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var MessageService
     */
    protected $messageService;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    protected $translatorService;

    public function __construct(
        EntityManagerInterface $em,
        PasswordService $passwordService,
        TranslatorService $translatorService,
        MessageService $messageService,
        EventDispatcherInterface $eventDispatcher,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository(User::class);
        $this->passwordService = $passwordService;
        $this->messageService = $messageService;
        $this->eventDispatcher = $eventDispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->translatorService = $translatorService;
    }

    public function getUser()
    {
        return $this->userRepository->findAll();
    }

    public function getUserByRole($role)
    {
        return $this->userRepository->getUserByRole($role);
    }

    public function editUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function newUser(User $user)
    {
        $passwordHashed = $this->passwordService->encode($user, $user->getPassword());
        $user->setPassword($passwordHashed);
        $this->em->persist($user);
        $this->em->flush();

        return $this->messageService->addSuccess('Votre inscription à bien été enregistré.');
    }

    public function deleteUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @param string $reference
     * @param string $password
     */
    public function setPassword(string $reference, string $password): void
    {
        $user = $this->userRepository->findOneBy(['reference' => $reference]);

        $user->setPassword($this->passwordService->encode($user, trim($password)));
        $this->em->flush();
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function update(User $user)
    {
        $user->setUpdatedAt(new \DateTime());
        $this->em->flush();

        return $this->messageService->addSuccess('Modification enregistrée.');
    }

    public function updateMedia(MediaProfil $mediaProfil)
    {
        $mediaProfil->setUpdatedAt(new \DateTime());
        $this->em->flush();

        return $this->messageService->addSuccess('Modification enregistrée.');
    }

    public function emailReset(String $email, User $user)
    {
        $checkMail = true;
        $userCheck = $this->userRepository->findOneByEmail($email);
        $userEmailReset = $this->userRepository->findOneByEmailReset($email);

        if ($userCheck instanceof User || $userEmailReset instanceof User) {
            $checkMail = false;
        }

        if ($checkMail) {
            $user->setUpdatedAt(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();
            return $this->messageService->addSuccess('Votre email de remplacement à bien été enregistré.');
        }

        return $this->messageService->addError('Cette adresse email a déjà été utilisé.');
    }

    public function resetPasswordUser(array $data, User $user)
    {
        if($this->passwordService->isValid($user, $data['password'])) {
            $user->setPassword($this->passwordService->encode($user, $data['passwordConfirmed']));
            $this->em->flush();

            return $this->messageService->addSuccess($this->translatorService->translate('message.flash.updatePassword'));
        }

        return $this->messageService->addError($this->translatorService->translate('message.flash.notUpdatePassword'));
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function removeIlltimate(User $user)
    {
        $user->setEmail('#####################@...........');
        $user->setUsername('#########################');
        $user->setLastName('#####################');
        $user->setPassword('#####################');
        $user->setFirstName('#####################');
        $user->setPhone('#####################');
        $user->setGender('################################');
        $this->em->persist($user);
        $this->em->flush();

        return $this->messageService->addSuccess('Suppression enregistrée.');
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    public function editAccount(User $user)
    {
        $this->em->flush();
    }

    /**
     * @param User   $user
     * @param string $password
     */
    public function editPassword(User $user, string $password)
    {
        if ($user instanceof User) {
            $user->setPassword($this->passwordService->encode($user, $password));
            $this->em->flush();
        }
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email)
    {
        $user = $this->userRepository->findOneByEmail($email);

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }

    /**
     * @param string $reference
     * @return null
     */
    public function findByReference(string $reference)
    {
        $user = $this->userRepository->findOneByReference($reference);

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }

    /**
     * @param string $token
     * @return array
     */
    public function checkToken(string $token)
    {
        $user = $this->userRepository->findoneByReference($token);

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function enable_password_dispatch(User $user)
    {
        $step = false;

        if (false === $step ) {
            $user->setPasswordDispatch($user->getPassword());
            $step = true;
        }

        if (true === $step) {
            $user->setPassword($this->passwordService->encode($user, User::KEY_PASSWORD_DISPATCH));
        }

        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function disabled_password_dispatch(User $user)
    {
        $user->setPassword($user->getPasswordDispatch());
        $user->setPasswordDispatch(null);
        $this->em->flush();
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function resetPassword(User $user)
    {
        $user->setPassword($this->passwordService->encode($user, $user->getPassword()));
        $this->em->flush();

        return $this->messageService->addSuccess($this->translatorService->translate('message.flash.updatepassword'));
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function activeUser(User $user)
    {
        if ($user->getState() !== 1) {
            $user->setState(1);
            $this->em->flush();

            return $this->messageService->addSuccess('Votre est compte maintenant validé.');
        }

        return $this->messageService->addError('Votre compte est déjà validé.');
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function registerAccount(User $user)
    {
        if ($this->findByEmail($user->getEmail())) {
            return $this->messageService->addError('Cette adresse email a déjà été utilisé.');
        }

        $user->setRole(User::ROLE_USER);
        $user->setUsername($user->getEmail());
        $pass = $this->passwordService->encode($user, $user->getPassword());
        $user->setPassword($pass);
        $user->setReference($this->referenceFormat());
        $user->setValidated(0);
        $this->em->persist($user);
        $this->em->flush();

        return $this->messageService->addSuccess('Création de compte enregistrée. Confirmez votre compte par email. Vérifiez vos courriers indésirables.');
    }

    /**
     * @param User $user
     * @param array $data
     */
    public function updatePassword(User $user, array $data): void
    {
        $password_old = trim($data['password_old']);
        $password = trim($data['password']);

        if (password_verify($password_old, $user->getPassword())) {
            $user->setPassword($this->passwordService->encode($user, $password));
            $this->em->persist($user);
            $this->em->flush();

            $this->messageService->addSuccess('Mot de passe mis à jour');
        } else {
            $this->messageService->addError('Ce mot de passe est incorrect !');
        }
    }

    /**
     * @param int $id
     *
     * @return int|string
     */
    public function formatIncrement(int $id)
    {
        return str_pad($id + 1, 6, 0, STR_PAD_LEFT);
    }

    /**
     * REP + ANNEE + MOIS + JOUR + TOKEN GENERER.
     *
     * @return string
     */
    public function referenceFormat()
    {
        return 'RF'.substr(date('Y'), 2).date('md').uniqid('', true);
    }

    /**
     * @param $user
     * @return RedirectResponse
     */
    public function redirectionAuth($user)
    {
        if (User::ROLE_ADMIN === $user->getRole()) {
            return new RedirectResponse($this->urlGenerator->generate('admin_home'));
        }

        return new RedirectResponse($this->urlGenerator->generate('student_home'));
    }
}