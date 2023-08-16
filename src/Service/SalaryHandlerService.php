<?php

namespace App\Service;

// use App\Entity\Expense;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Yectep\PhpSpreadsheetBundle\Factory;

class SalaryHandlerService {
    
    public function __construct( ) {
       // Something can happen here
    }

    public function checkLastDayOfMonth($someDate = null) {
        if(!$someDate) {
            $someDate = date('Y-m-d');
        }
        echo 'Someday ', $someDate.PHP_EOL;

        $lastDay = date('Y-m-t', strtotime($someDate));

        $dayOfWeek = date('l', strtotime($lastDay));

        echo 'last day '.$lastDay.PHP_EOL;

        echo 'Day of week '.$dayOfWeek.PHP_EOL;

        $paymentDate = $lastDay;
        
        if($dayOfWeek === 'Sunday'){
            $lastFriday = date('Y-m-d', strtotime("{$lastDay} - 2 days"));
            $paymentDate = $lastFriday;
        } 
        if($dayOfWeek === 'Saturday') {
            $lastFriday = date('Y-m-d', strtotime("{$lastDay} - 1 days"));
            $paymentDate = $lastFriday;
        }
        echo '&&&&&&&& Salary paid on ', $paymentDate.PHP_EOL;
        return date('Y-m-d',strtotime($paymentDate ));
    }

    public function getfifteenthDayOfMonth($year = null, $month =null) {
        
        // Get the current month and year
        $currentYear = ($year)?$year:date('Y');
        $nextMonth = (($month)?(($month+1)% 12):(date('m', strtotime("+1 months"))+1));

        if ($month === 12) {
            $currentYear = $currentYear +1;
        }
        // Create a date string for the 15th day of the current month
        $someDate = "$currentYear-$nextMonth-15";

        $fifteenthDay = date('Y-m-d', strtotime($someDate));

        $dayOfWeek = date('l', strtotime($fifteenthDay));

        echo "The date is on ". $fifteenthDay.PHP_EOL;

        echo "The 15th day of the next month falls on a $dayOfWeek.".PHP_EOL;

        $paymentDate = $fifteenthDay;
        
        if($dayOfWeek === 'Sunday'){
            $nextWednesday = date('Y-m-d', strtotime("{$fifteenthDay} +2 days"));
            $paymentDate = $nextWednesday;
        } 
        if($dayOfWeek === 'Saturday') {
            $nextWednesday = date('Y-m-d', strtotime("{$fifteenthDay} +3 days"));
            $paymentDate = $nextWednesday;
        }
        echo '&&&&&&&& Bonuis paid on ', $paymentDate.PHP_EOL;
        return date('Y-m-d',strtotime($paymentDate));
    }
}
