<?php

function week_of_month($date) {
     $first_of_month = new DateTime($date->format('Y-m-1'));
     $day_of_first = $first_of_month->format('N');
     $day_of_month = $date->format('j');
     return floor(($day_of_first + $day_of_month - 1) / 7) + 1;
 }

function weeks_in_month($month_date) {
    $start = new DateTime($month_date->format('Y-m'));
    $days = (clone $start)->add(new DateInterval('P1M'))->diff($start)->days;
    $offset = $month_date->format('N') - 1;
    return ceil(($days + $offset)/7);
  }

function isHolidayDay() {
    $result = false;
    $holidays = array(
        array('date_of_holiday'=>'01.01', 'from_mon_day'=>'1', 'mon'=>'1',
            'week_day'=>'', 'mon_week'=>'', 'to_mon_day'=>''),
        array('date_of_holiday'=>'01.07', 'from_mon_day'=>'7', 'mon'=>'1',
            'week_day'=>'', 'mon_week'=>'', 'to_mon_day'=>''),
        array('date_of_holiday'=>'05.01-05.02', 'from_mon_day'=>'1', 'mon'=>'5',
            'week_day'=>'', 'mon_week'=>'', 'to_mon_day'=>'2'),
        array('date_of_holiday'=>'Пн 3й нед янв', 'from_mon_day'=>'', 'mon'=>'1',
            'week_day'=>'1', 'mon_week'=>'3', 'to_mon_day'=>''),
        array('date_of_holiday'=>'Пн пос нед мар', 'from_mon_day'=>'', 'mon'=>'3',
            'week_day'=>'1', 'mon_week'=>'-1', 'to_mon_day'=>''),
        array('date_of_holiday'=>'Чт 4й нед ноя', 'from_mon_day'=>'', 'mon'=>'11',
            'week_day'=>'4', 'mon_week'=>'4', 'to_mon_day'=>'')
    );

    $curDate = new DateTime();

    //$curDate = DateTime::createFromFormat('d-m-Y', '19-11-2020');
    foreach ($holidays as &$value) {
        if($value['mon'] == $curDate->format("m")){
            if(!empty($value['from_mon_day'])){
                $from_day = $value['from_mon_day'];
                $to_day = empty($value['to_mon_day']) ? $from_day : $value['to_mon_day'];
                if($from_day <= $curDate->format("d") &&  $to_day >= $curDate->format("d")){
                    $result = true;
                }
            }else{
                $week_day = $value['week_day'];
                $format = 'd-m-Y';
                $createDate = DateTime::createFromFormat($format, '01-'.$value['mon'].'-'.$curDate->format('Y'));
                $mon_week = $value['mon_week'] < 0 ? (intval(weeks_in_month($createDate, true)) + intval($value['mon_week'])) + 1 : $value['mon_week'];
                if($week_day == $curDate->format('N') && $mon_week == week_of_month($curDate)){
                    $result = true;
                }
            }
        }
    }
    $resString = $result ? 'true' : 'false';
    echo 'Date ' . $curDate->format('d-m-Y') . ' is holiday: ' . $resString;
    return $result;
}

isHolidayDay(); 

?>