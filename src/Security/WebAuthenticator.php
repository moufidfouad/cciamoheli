<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class WebAuthenticator extends AbstractLoginFormAuthenticator //implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'web_login';

    /** @var UrlGeneratorInterface */
    private $urlGenerator;
    /**  @var UserProviderInterface */
    private $userProvider;
    /**  @var CsrfTokenManagerInterface */
    private $csrfTokenManager;
    /**  @var UserPasswordHasherInterface */
    private $passwordEncoder;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        UserProviderInterface $userProvider,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordHasherInterface $passwordEncoder
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->userProvider = $userProvider;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    /*public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        dd($credentials);
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $userProvider->loadUserByIdentifier($credentials['username']);
        
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }*/
    

    /*public function checkCredentials($credentials, UserInterface $user): bool
    {
        dd($credentials);
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }*/
  
    public function authenticate(Request $request): PassportInterface
    {
        $credentials = $this->getCredentials($request);

        $username = $credentials['username'] ?? '';
        $password = $credentials['password'] ?? '';
        $csrf_token = $credentials['csrf_token'];
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrf_token))) {
            throw new InvalidCsrfTokenException();
        }

        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $passport = new Passport(
            new UserBadge($username, function($id){
                $user = $this->userProvider->loadUserByIdentifier($id);
                return $user;
            }),
            new PasswordCredentials($password)
        );
        $passport->addBadge(new CsrfTokenBadge('authenticate', $csrf_token));
        //dd($passport->getBadges());
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('web_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    private function getUser(string $identifier)
    {
        $user = $this->userProvider->loadUserByIdentifier($identifier);
        if (!($user instanceof User)) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        } elseif (!$user->getEnabled()){
            throw new DisabledException('Account is disabled.');
        }
        return $user;
    }
}
