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
