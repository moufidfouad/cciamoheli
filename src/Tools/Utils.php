<?php

namespace App\Tools;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class Utils
{
    public static function setUserPasswordFromUsername(User $user,UserPasswordHasherInterface $encoder)
    {
        $user->setPassword($encoder->hashPassword($user,$user->getUserIdentifier()));
    }

    public static function getToken(int $length)
    {
        return rtrim(strtr(base64_encode(random_bytes($length)),'+/','-_'),'=');
    }
}