<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use App\Machine\CigaretteMachine;
use App\Machine\PurchaseTransactionInterface;
use App\Machine\PurchaseTransaction;

/**
 * Class CigaretteMachine
 * @package App\Command
 */
class PurchaseCigarettesCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('packs', InputArgument::REQUIRED, "How many packs do you want to buy?");
        $this->addArgument('amount', InputArgument::REQUIRED, "The amount in euro.");
    }

    /**
     * @param InputInterface   $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemCount = (int) $input->getArgument('packs');
        $amount = (float) \str_replace(',', '.', $input->getArgument('amount'));

        $purchase = new PurchaseTransaction($itemCount, $amount);
        $cigaretteMachine = new CigaretteMachine();
        $resp = $cigaretteMachine->execute($purchase);

        if($resp == "nofunds") {
            $output->writeln('<info>NO FUNDS!</info>');
            $output->writeln('Less money given than total cost of amount...');
            return 0;
        }

        $output->writeln('You bought <info>'.$itemCount.'</info> packs of cigarettes for <info>'.(float)$amount.'</info>, each for <info>'.$cigaretteMachine->getItemPrice().'</info>. ');
        $output->writeln('Your change is: <info>'.$cigaretteMachine->getChangeAmount().'</info>');

        $change = $cigaretteMachine->getChange();

        $table = new Table($output);
        $table
        ->setHeaders(array('Coins', 'Count'))
        ->setRows($change);

        $table->render();

        return 1;
    }
}