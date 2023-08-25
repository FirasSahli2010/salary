<?php
namespace App\Service;

// use App\Entity\Expense;
use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Yectep\PhpSpreadsheetBundle\Factory;

class SalarySheetGeneratorService {
    private string $fileName;
    private SalaryHandlerService $salaryHandlerService;
    private Factory $excelBundle;
    private Spreadsheet $spreadsheet;
    private int $currentIndex = 1;
    private string $reportBasePath;
    private ?string $reportPath = null;
    public function  __construct(
        Factory $excelBundle,
        ParameterBagInterface $parameterBag,
        SalaryHandlerService $salaryHandlerService,
    ) {
        $this->salaryHandlerService = $salaryHandlerService;
        $this->excelBundle = $excelBundle;
        $this->spreadsheet = $excelBundle->createSpreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);
    }
    private function writeHeader() {
        $this
            ->spreadsheet
            ->getActiveSheet()
            ->setCellValue("A$this->currentIndex", "Month")
            ->setCellValue("B$this->currentIndex", "Salary paid on")
            ->setCellValue("C$this->currentIndex", "Commission paid on");
        $this->currentIndex++;
    }

     private function writeRow($month, $salaryPaidOn, $commissionPaidOn) {
        $this
            ->spreadsheet
            ->getActiveSheet()
            ->setCellValue("A$this->currentIndex", $month)
            ->setCellValue("B$this->currentIndex", $salaryPaidOn)
            ->setCellValue("C$this->currentIndex", $commissionPaidOn);
        $this->currentIndex++;
    }

    private function resizeColumns() {
        $columns = ['A', 'B', 'C'];
        foreach ($columns as $column) {
            $this->spreadsheet
                ->getActiveSheet()
                ->getColumnDimension($column)
                ->setWidth(50);
        }
    }

    private function saveReport() {
        $this->reportPath = "./reports/report_" . time() . ".xlsx";
        $writer = $this->excelBundle->createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($this->reportPath);
    }

    public function generateSheet($csv, $xls, $wholeYear, $fromWhichMonth, $whichYear ) {
        $beginMonth = ($wholeYear)?intval('01'):(($fromWhichMonth)?intval($fromWhichMonth):intval(date('m' )));
        $ofYear = $whichYear?intval($whichYear):intval(date('Y'));
        
        $fp = fopen('./reports/report'. time() .'.csv', "w");
    
        $results = [];
        if($csv || (!$xls)) {
            $currentMonth = $beginMonth;
            while ($currentMonth <= 12) {
                if($currentMonth ) {
                    $month =$currentMonth;
                    $year = $ofYear;
                    if($month && $year && ($month >= 1) && ($month <= 12) ) {
                        $results= array(
                            date('M', strtotime($year.'-'.$month.'-1')),
                            $this->salaryHandlerService->checkLastDayOfMonth(date('Y-m-d', strtotime($year.'-'.$month.'-01'))),
                            $this->salaryHandlerService->getfifteenthDayOfMonth($year, $month)
                        );
                        fputcsv(
                            $fp, 
                            [
                                ('Month: '. $results[0]),  
                                ('Payment date of base salary '.date('\T\h\e d\t\h \o\f M Y',strtotime($results[1]))), 
                                ('Payment date of bonus: '.date('\T\h\e d\t\h \o\f M Y',strtotime($results[2])))
                            ],
                            ';' 
                        );  
                    }
                }
                $currentMonth += 1;
            }
        }

        if($xls) {
            $this->writeHeader();
            $currentMonth = $beginMonth;
            while ($currentMonth <= 12) {
                if($currentMonth ) {
                    $month =$currentMonth;
                    $year = $ofYear;
                    if($month && $year && ($month >= 1) && ($month <= 12) ) {
                        $results= array(
                            date('M', strtotime($year.'-'.$month.'-1')),
                            $this->salaryHandlerService->checkLastDayOfMonth(date('Y-m-d', strtotime($year.'-'.$month.'-01'))),
                            $this->salaryHandlerService->getfifteenthDayOfMonth($year, $month)
                        );
                        $this->writeRow($results[0], $results[1],$results[2]);
                    }
                }
                $currentMonth += 1;
            }
            $this->resizeColumns();
            $this->saveReport();
        }
    }
}
