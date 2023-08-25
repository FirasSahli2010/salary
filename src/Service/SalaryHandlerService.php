<?php

namespace App\Service;

class SalaryHandlerService {
    
    public function __construct( ) {
    }
    
    public function checkLastDayOfMonth($someDate = null) {
        if(!$someDate) {
            $someDate = date('Y-m-d');
        }
        $lastDay = date('Y-m-t', strtotime($someDate));
        $dayOfWeek = date('l', strtotime($lastDay));
        $paymentDate = $lastDay;
        if($dayOfWeek === 'Sunday'){
            $lastFriday = date('Y-m-d', strtotime("{$lastDay} - 2 days"));
            $paymentDate = $lastFriday;
        } 
        if($dayOfWeek === 'Saturday') {
            $lastFriday = date('Y-m-d', strtotime("{$lastDay} - 1 days"));
            $paymentDate = $lastFriday;
        }
        return date('Y-m-d',strtotime($paymentDate ));
    }

    public function getfifteenthDayOfMonth($year = null, $month =null) {
        $nextMonthTheFilfteenth = date('Y-m-d', strtotime('+1 month', strtotime($year.'-'.$month.'-15')));
        $dayOfWeek = date('l', strtotime($nextMonthTheFilfteenth));
        $paymentDate = $nextMonthTheFilfteenth;
        
        if($dayOfWeek === 'Sunday'){
            $nextWednesday = date('Y-m-d', strtotime("{$nextMonthTheFilfteenth} +2 days"));
            $paymentDate = $nextWednesday;
        } 
        if($dayOfWeek === 'Saturday') {
            $nextWednesday = date('Y-m-d', strtotime("{$nextMonthTheFilfteenth} +3 days"));
            $paymentDate = $nextWednesday;
        }

        return date('Y-m-d',strtotime($paymentDate));
    }
}
