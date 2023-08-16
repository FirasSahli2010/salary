<?php
namespace App\Service;

// use App\Entity\Expense;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Yectep\PhpSpreadsheetBundle\Factory;

class SalarySheetGeneratorService {
    
    private int $currentIndex = 1; // To be used when adding multiple monthes 
    private ?string $reportPath = null;  // The path to the folder where the report will be generated, this now not used but it can be added
    private string $fileName;
    private SalaryHandlerService $salaryHandlerService;

    private $month = 0;
    private $year = 0;
    public function  __construct(
        SalaryHandlerService $salaryHandlerService
    ) 
    {
       $this->salaryHandlerService = $salaryHandlerService;
    }

    public function setFileName($fileName = 'salarySheet') {
        $this->fileName = $fileName;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function setYear($year= null) {
        if (!$year) {
            $year = date('Y');
        }
        $this->year = $year;
    }
    public function getYear() {
        return $this->year;
    }
    public function setMonth($month = null) {
        if (!$month) {
            $month = date('m');
        }
        $this->month = $month;
    }
    public function generateSheet($fileName = 'salarySheet', $year = null, $month= null) {
        if((!$month) || ($month > 12 ) || ($month < 1))  {
            $month = date('m');
        }

        if(!$year){
            $year = date('Y');
        }
        echo ' Year '.$year.PHP_EOL;
        echo ' month '.$month.PHP_EOL;

        $this->fileName = $fileName;

        if(!$this->fileName || $this->fileName ==='') {
            $this->fileName = 'salarySheet';
        }

        $fileNameWithExte = $this->fileName.'.csv';
        
        $fp = fopen('./reports/'.$fileNameWithExte, "w");
        fputcsv(
            $fp, // The file pointer
            array('month','Payment of salary', 'Payment of bonus'), // The fields
            ';' // The delimiter
        );

        $results= array(
            date('M', strtotime($this->year.'-'.$this->month.'-1')),
            $this->salaryHandlerService->checkLastDayOfMonth(date('Y-m-d', strtotime($this->year.'-'.$this->month.'-01'))),
            $this->salaryHandlerService->getfifteenthDayOfMonth($year, $month)
        );
        
        fputcsv(
            $fp, 
            $results, // The data
            ';' 
        );      
        

    }
}