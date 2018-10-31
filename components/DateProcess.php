<?php
namespace app\components;

class DateProcess
{
    public  $fromTimeZone = 'UTC';
    public  $toTimeZone = 'Asia/Ho_Chi_Minh';
    public  $format = 'Y-m-d';
    public static function convert($datetime, $format='Y-m-d', $fromTimeZone = 'UTC', $toTimeZone = 'Asia/Ho_Chi_Minh')
    {
        $dt = new \DateTime($datetime, new \DateTimeZone($fromTimeZone));
        $dt->setTimezone(new \DateTimeZone($toTimeZone));
        return $dt->format($format);
    }
    public function resultTheDay($date)
    {
        $current = $this->convert(date($this->format), $this->format, $this->fromTimeZone, $this->toTimeZone);
        $curr_date = strtotime($current);
        $the_date = strtotime($date);
        $datediff = $the_date - $curr_date;
        $diff = floor($datediff/(60*60*24));
        if($diff == 0)
        {
            return 'Today';
        }
        else if($diff > 1)
        {

            $day_index = date('w',strtotime($current));
            $num_day = 7-$day_index;
            if ($date == date('Y-m-d', strtotime($current.' + 2 day'))) {
              return 'The day after tomorrow';
            }
            else if ($date == date('Y-m-d', strtotime($current.' + '.$num_day.' day'))) {
              return 'This week';
            }
            else if ($date == date('Y-m-d', strtotime('sunday',strtotime('next week')))) {
              return 'Next week';
            }
            else if ($date == date('Y-m-d', strtotime('last day of this month', strtotime($current)))) {
              return 'This month';
            }
            else if ($date == date('Y-m-d', strtotime('last day of this month', strtotime('next month', strtotime($current))))) {
              return 'Next month';
            }
            else{
              return '';
            }
        }
        else if($diff > 0)
        {
            return 'Tomorrow';
        }
        else if($diff < -1)
        {
            return '';
        }
        else
        {
            return 'Yesterday';
        }
    }
}
