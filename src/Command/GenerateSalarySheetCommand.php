<?php

namespace App\Command;

use App\Service\SalaryHandlerService;
use App\Service\SalarySheetGeneratorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Console\Input\InputDefinition;

#[AsCommand(
    name: 'GenerateSalarySheetCommand',
    description: 'GEnerate Salarry and bonus sheet',
)]
class GenerateSalarySheetCommand extends Command
{
    protected static $defaultName = 'generateSalarySheet';
    private SalarySheetGeneratorService $salarySheetGenerator;


    public function __construct(
        SalarySheetGeneratorService $salarySheetGenerator
    ) 
    {
        parent::__construct();
        $this->salarySheetGenerator = $salarySheetGenerator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates salary and bonus payment sheet')
            ->setHelp(
                'This command helps you generate a sheet contains alary and bonus payment days based on provided arguments'
            )
            ->addOption('year', null, InputOption::VALUE_NONE, 'Year ')
            ->addOption('month', null, InputOption::VALUE_NONE, 'Month ')
            ->addOption('file', null, InputOption::VALUE_NONE, 'file name')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption(
                            'file',
                            'f',
                            InputOption::VALUE_OPTIONAL,
                            'File name, where the sarary sheet will be generated'
                        ),
                        new InputOption(
                            'year',
                            'y',
                            InputOption::VALUE_OPTIONAL,
                            'sheet year, for which the sheet is generated'
                        ),
                        new InputOption(
                            'month',
                            'm',
                            InputOption::VALUE_OPTIONAL,
                            'sheet month, for which the sheet is generated'
                        )
                    ]
                )
            );
        ;
    }

    protected function generateReport($fileName = 'salarysheet', $year = null, $month = null) {

        if (!$year) {
            $year = date('Y');
        }
        if(!$month) {
            $month = date('m');
        }
        if(!$fileName) {
            $fileName = 'salarySheet';
        }
        $this->salarySheetGenerator->generateSheet($fileName, $year, $month);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $io = new SymfonyStyle($input, $output);
        
        $fileName = $input->getOption('file');
        $year = $input->getOption('year');
        $month = $input->getOption('month');
        $this->salarySheetGenerator->setFileName($fileName);
        $this->salarySheetGenerator->setYear($year);
        $this->salarySheetGenerator->setMonth($month);

        echo 'Year '.$year.PHP_EOL;
        echo 'month '.$month.PHP_EOL;

        $this->generateReport($fileName, $year, $month);
        $io->success('Salary sheet Generatetion done!, Pass --help to see different options.');
        
        return Command::SUCCESS;
    }
}
