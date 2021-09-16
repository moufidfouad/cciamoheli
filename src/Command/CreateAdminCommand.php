<?php

namespace App\Command;

use App\Entity\Admin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    protected static $defaultName = 'create:admin';
    /**@var EntityManagerInterface */
    private $em;

    /**@var UserPasswordHasherInterface */
    private $hasher;

    public function __construct(
		EntityManagerInterface $em,
		UserPasswordHasherInterface $hasher
	)
    {
        parent::__construct();
        $this->em = $em;
        $this->hasher = $hasher;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getOption('username');
        $admin = $this->em->getRepository(User::class)->findOneBy([
            'username' => $username
        ]);
        if (\is_null($admin)) {
            $admin = new Admin($username);
            $admin->setPassword($this->hasher->hashPassword($admin,$admin->getUserIdentifier()));
            try{
                $this->em->persist($admin);
                $this->em->flush();

                $io->success(sprintf('Le compte administrateur vient d\'être enregistré'));
            }catch(Exception $e){
                $io->note(sprintf($e->getMessage()));
            }            
        }else{
            $io->note(sprintf('Un compte administrateur a déjà été enregistré'));
        }        

        return Command::SUCCESS;
    }
}
