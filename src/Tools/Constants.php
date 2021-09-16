<?php

namespace App\Tools;

use App\Entity\User;

final class Constants 
{
    const GENRE_HOMME = 'H';
    const GENRE_FEMME = 'F';
    static $GENRE_CHOICES = [
        'genre.homme' => self::GENRE_HOMME,
        'genre.femme' => self::GENRE_FEMME
    ];

    const REPOS_PERMISSION = 'REPOS_PERMISSION';
    const REPOS_CONGE_ADMIN = 'REPOS_CONGE_ADMIN';
    const REPOS_MARIAGE = 'REPOS_MARIAGE';
    const REPOS_MALADIE = 'REPOS_MALADIE';
    const REPOS_DISPONIBILITE = 'REPOS_DISPONIBILITE';
    static $REPOS_CHOICES = [
        'repos.permission' => self::REPOS_PERMISSION,
        'repos.conge' => self::REPOS_CONGE_ADMIN,
        'repos.mariage' => self::REPOS_MARIAGE,
        'repos.maladie' => self::REPOS_MALADIE,
        'repos.disponibilite' => self::REPOS_DISPONIBILITE
    ];

    const SORTIE_DEMISSION = 'SORTIE_DEMISSION';
    const SORTIE_LICENCIEMENT = 'SORTIE_LICENCIEMENT';
    const SORTIE_MISAPIED = 'SORTIE_MISAPIED';
    const SORTIE_CHOMAGE = 'SORTIE_CHOMAGE';
    static $SORTIE_CHOICES = [
        'sortie.demission' => self::SORTIE_DEMISSION,
        'sortie.licenciement' => self::SORTIE_LICENCIEMENT,
        'sortie.misapied' => self::SORTIE_MISAPIED,
        'sortie.chomage' => self::SORTIE_CHOMAGE
    ];

    const ENTREE_RECRUTEMENT = 'ENTREE_RECRUTEMENT';
    const ENTREE_NOMINATION = 'ENTREE_NOMINATION';
    const ENTREE_MANDAT = 'ENTREE_MANDAT';
    static $ENTREE_CHOICES = [
        'entree.recrutement' => self::ENTREE_RECRUTEMENT,
        'entree.nomination' => self::ENTREE_NOMINATION,
        'entree.mandat' => self::ENTREE_MANDAT
    ];

    const MUTATION_AFFECTATION= 'MUTATION_AFFECTATION';
    const MUTATION_NOMINATION = 'MUTATION_NOMINATION';
    static $MUTATION_CHOICES = [
        'mutation.affectation' => self::MUTATION_AFFECTATION,
        'mutation.nomination' => self::MUTATION_NOMINATION
    ];

    const ANNONCE_ARTICLE = 'ANNONCE_ARTICLE';
    const ANNONCE_PUBLICATION = 'ANNONCE_PUBLICATION';
    const ANNONCE_RAPPORT = 'ANNONCE_RAPPORT';
    static $ANNONCE_CHOICES = [
        'annonce.article' => self::ANNONCE_ARTICLE,
        'annonce.publication' => self::ANNONCE_PUBLICATION,
        //'annonce.rapport' => self::ANNONCE_RAPPORT
    ];

    public static function getEsChoices()
    {
        return array_merge(
            self::$ENTREE_CHOICES,
            self::$MUTATION_CHOICES,
            self::$REPOS_CHOICES,
            self::$SORTIE_CHOICES
        );
    }

    static $ROLES_CHOICES = [
        //'role.default' => User::ROLE_DEFAULT,
        'role.article' => User::ROLE_ADMIN_ARTICLE,
        'role.publication' => User::ROLE_ADMIN_PUBLICATION,
        'role.evenement' => User::ROLE_ADMIN_EVENEMENT,
        'role.bureau' => User::ROLE_ADMIN_BUREAU,
        'role.fonction' => User::ROLE_ADMIN_FONCTION,
        'role.es' => User::ROLE_ADMIN_ES,
        'role.mission' => User::ROLE_ADMIN_MISSION,
        'role.activite' => User::ROLE_ADMIN_ACTIVITE
    ];
}