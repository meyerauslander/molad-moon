<?php
/**
 * Calculate how many months it will take from the first molad of creation until the molad falls out on a specific target day and time
 * 
 * Based on tos D"H "V"Litikoofa" Rosh Hashana 8a - 8b
 */

// Tos say the first year molad is Day 2, 5th hour, 204 chalakim (one lunar year before Day 6 )
define('FIRST_MOLAD_DAY',2);
define('FIRST_MOLAD_HOUR',5);
define('FIRST_MOLAD_CHALAKIM',204);

// All agree one month is 29 days 12 hours and 793 chalakim
define('ONE_LUNAR_MONTH_DAYS',1); // 1 is 29 % 7, the other 28 days don't change the day of the week.
define('ONE_LUNAR_MONTH_HOURS',12);
define('ONE_LUNAR_MONTH_CHALAKIM',793);

define('CHALAKIM_PER_HOUR',1080);
define('HOURS_PER_DAY',24);
define('DAYS_PER_WEEK',7);

// According to my calculation, molad for tishrei in 5782, 3 20 701
$target_day = 3;
$target_hour = 20;
$target_chalakim = 701;

// According to Luach Ezras Torah, molad for tishrei in 5782, 2 5 497 
// $target_day = 2;
// $target_hour = 5;
// $target_chalakim = 497;

// test
// $target_day = 3;
// $target_hour = 17;
// $target_chalakim = 997;


class time{
  private $days, $hour, $chalakim;
  
  public function __construct($days, $hour, $chalakim){
    $this->days = $days;
    $this->hour = $hour;
    $this->chalakim = $chalakim;
  }

  /**
   * Add an amount of time and store the resulting time of the week.
   */
  public function add($days, $hours, $chalakim){
    // Chalakim
    $total_chalakim = $this->chalakim + $chalakim;
    $this->hour += intdiv($total_chalakim, CHALAKIM_PER_HOUR);
    $this->chalakim = $total_chalakim % CHALAKIM_PER_HOUR;
    

    // Hours
    $total_hours = $this->hour + $hours;
    $this->days += intdiv($total_hours, HOURS_PER_DAY);
    $this->hour = $total_hours % HOURS_PER_DAY;
    
    // Days
    $total_days = $this->days + $days;
    $this->days = $total_days % DAYS_PER_WEEK;
    // $this->print();
  }

  public function is_equal($days, $hour, $chalakim ){
    return ($this->days == $days) && ($this->hour == $hour) && ($this->chalakim == $chalakim);
  }

  public function print(){
    echo "The time is currently day $this->days, hour $this->hour, and $this->chalakim chalakim.\n";
  }
}

$current_time = new time(FIRST_MOLAD_DAY, FIRST_MOLAD_HOUR, FIRST_MOLAD_CHALAKIM);

// There have not yet been 75,000 moladim since creation
//
for ($i=1;$i<75000;$i++){
// for ($i=1;$i<4;$i++){
  // Chalkim
  $current_time->add(ONE_LUNAR_MONTH_DAYS,ONE_LUNAR_MONTH_HOURS,ONE_LUNAR_MONTH_CHALAKIM);
  if ( $current_time->is_equal($target_day,$target_hour,$target_chalakim) ) {
    $current_time->print();
    echo "Hit the target day on the ${i}th molad!\n";
  }
  
}


?>