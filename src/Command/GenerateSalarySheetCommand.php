<?php

namespace App\Command;

use App\Service\SalarySheetGeneratorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputDefinition;

#[AsCommand(
    name: 'GenerateSalarySheetCommand',
    description: 'Generate Salary and bonus sheet for a year',
)]
class GenerateSalarySheetCommand extends Command
{
    protected static $defaultName = 'generateSalarySheet';
    private SalarySheetGeneratorService $salarySheetGenerator;
    protected static $defaultPathToFile = 'GenerateSalarySheetCommand';

    public function __construct(
        $projectDir,
        SalarySheetGeneratorService $salarySheetGenerator
    ) {
        parent::__construct();
        $this->salarySheetGenerator = $salarySheetGenerator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates salary and bonus payment sheet')
            ->setHelp(
                'This command helps you generate a sheet contains an salary and bonus payment days based on provided arguments'
            )
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption(
                            'whichYear',
                            'w',
                            InputOption::VALUE_OPTIONAL,
                            'For which year, default current year, default current year',
                            intval(date('Y'))
                        ),
                        new InputOption(
                            'fromWhichMonth',
                            'f',
                            InputOption::VALUE_OPTIONAL,
                            'from which month to start generation, if current year default ceurrent month, otherwise default = 01',
                            1
                        ),
                        new InputOption(
                            'csv',
                            'c',
                            InputOption::VALUE_NONE,
                            'Generatet the file in CSV format'
                        ),
                        new InputOption(
                            'xls',
                            'x',
                            InputOption::VALUE_NONE,
                            'Generatet the file in XSL format'
                        ),
                        new InputOption(
                            'year',
                            'y',
                            InputOption::VALUE_NONE,
                            'Generate sheet for a whole given year'
                        )
                    ]
                )
            );
    }

       protected function execute(InputInterface $input, OutputInterface $output): int {
        
        $io = new SymfonyStyle($input, $output);
        
        $csvPath = 'report.csv';//$input->getOption('file');

        $year = $input->getOption('year');
        $csv = $input->getOption('csv');
        $xls = $input->getOption('xls');

        $whichYear = $input->getOption('whichYear');
        $fromWhichMonth = $input->getOption('fromWhichMonth');

        if (!$fromWhichMonth) {
            if($year || $whichYear!== intval(date('Y')) ) {
                $fromWhichMonth = '01';
            } else {
                $fromWhichMonth = date('m');
            }
        }


        $this->salarySheetGenerator->generateSheet($csv, $xls, $year, $fromWhichMonth, $whichYear);
        $io->success('Salary sheet Generatetion done!, Pass --help to see different options.');
        
        return Command::SUCCESS;
    }
}
