<?php
/**
 * Calculate how many months it will take from the first molad of creation until the molad falls out on a specific target day and time
 * 
 * Based on tos D"H "V"Litikoofa" Rosh Hashana 8a - 8b
 */

// Tos say the first year molad is Day 2, 5th hour, 204 chalakim (one lunar year before Day 6 )
// define('FIRST_MOLAD_DAY',2);
// define('FIRST_MOLAD_HOUR',5);
// define('FIRST_MOLAD_CHALAKIM',204);

// define('FIRST_MOLAD_DAY',2);
// define('FIRST_MOLAD_HOUR',11);
// define('FIRST_MOLAD_CHALAKIM',497);

// Tishrei 5781 5 20 701
define('FIRST_MOLAD_DAY',5);
define('FIRST_MOLAD_HOUR',20);
define('FIRST_MOLAD_CHALAKIM',701);

// All agree one month is 29 days 12 hours and 793 chalakim
define('ONE_LUNAR_MONTH_DAYS',1); // 1 is 29 % 7, the other 28 days don't change the day of the week.
define('ONE_LUNAR_MONTH_HOURS',12);
define('ONE_LUNAR_MONTH_CHALAKIM',793);

define('CHALAKIM_PER_HOUR',1080);
define('HOURS_PER_DAY',24);
define('DAYS_PER_WEEK',7);

define('MONTHS_PER_MACHZOR',235);
define('YEARS_PER_MACHZOR',19);

global $target_day, $target_hours, $target_chalakim;
// According to my calculation, molad for tishrei in 5782, 3 20 701
// $target_day = 3;
// $target_hours = 19; //20
// $target_chalakim = 701;

// According to Luach Ezras Torah, molad for tishrei in 5782, Monday night 27m 11 chalakim after 11
// 27m = 486ch  after 11 at night =? 11h
$target_day = 2;
$target_hours = 11; //4; //5
$target_chalakim = 497;

// According to Luach Ezras Torah, molad for chashvan in 5782, Monday night 11m 12ch after 12
// 11m = 198ch  after 12 at night =? 12h ??????
// $target_day = 2;
// $target_hours = 12; //4; //5
// $target_chalakim = 210;

// According to Luach Ezras Torah, molad for Tishrei in 5781, Thursday 2:38pm 17ch
// 38m = 684ch  2:38pm = 12 + 8 = 20
// $target_day = 5;
// $target_hours = 20; 
// $target_chalakim = 701;

// Molad of Adam HaRishone
// $target_day = 6;
// $target_hours = 14;
// $target_chalakim = 0;


// According to Luach Ezras Torah, molad for nisan in 5782, Friday night 36m 0 chalakim after 4
// 36m = 648ch  after 4 at night =? 4h
// $target_day = 6;
// $target_hours = 4; //4; //5
// $target_chalakim = 648;

// test
// $target_day = 3;
// $target_hours = 17;
// $target_chalakim = 997;


class time_of_week{
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

function main(){
  global $target_day, $target_hours, $target_chalakim;
  $current_time = new time_of_week(FIRST_MOLAD_DAY, FIRST_MOLAD_HOUR, FIRST_MOLAD_CHALAKIM);

  // There have not yet been 75,000 moladim since creation.
  for ($molad=1;$molad<75000;$molad++){
  // for ($molad=1;$molad<13;$molad++){
    // Chalkim
    $current_time->add(ONE_LUNAR_MONTH_DAYS,ONE_LUNAR_MONTH_HOURS,ONE_LUNAR_MONTH_CHALAKIM);
    if ( $current_time->is_equal($target_day,$target_hours,$target_chalakim) ) {
      $current_time->print();
      echo "Hit the target day after the ${molad}th molad!\n";
      $year_and_month = find_the_year_and_month($molad);
      echo "The years past are $year_and_month->year and the months past are $year_and_month->month!\n";
    }
    
  }
}


global $month_lookup;
$month_lookup = []; //To store a maping between the machzor month number and the corsponding year/month-of-that-year in that machzor.
build_month_lookup();
// var_dump($month_lookup);

function build_month_lookup(){
  global $month_lookup;
  for($i=0;$i<236;$i++){
    $year_and_month=new stdClass();
    switch(true){
      case($i<13):
        $year_and_month->year=0;
        $year_and_month->month=$i;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<25):
        $year_and_month->year=1;
        $year_and_month->month=$i-12;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<38): //3: leap year
        $year_and_month->year=2;
        $year_and_month->month=$i-24;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<50):
        $year_and_month->year=3;
        $year_and_month->month=$i-37;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<62): 
        $year_and_month->year=4;
        $year_and_month->month=$i-49;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<75): //6: leap year
        $year_and_month->year=5;
        $year_and_month->month=$i-61;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<87): 
        $year_and_month->year=6;
        $year_and_month->month=$i-74;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<100): //8: leap year
        $year_and_month->year=7;
        $year_and_month->month=$i-86;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<112): 
        $year_and_month->year=8;
        $year_and_month->month=$i-99;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<124):
        $year_and_month->year=9;
        $year_and_month->month=$i-111;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<137): //11: leap year
        $year_and_month->year=10;
        $year_and_month->month=$i-123;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<149): 
        $year_and_month->year=11;
        $year_and_month->month=$i-136;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<161): 
        $year_and_month->year=12;
        $year_and_month->month=$i-148;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<174): //14: leap year
        $year_and_month->year=13;
        $year_and_month->month=$i-160;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<186): 
        $year_and_month->year=14;
        $year_and_month->month=$i-173;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<198):
        $year_and_month->year=15;
        $year_and_month->month=$i-185;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<211): //17: leap year
        $year_and_month->year=16;
        $year_and_month->month=$i-197;
        $month_lookup[$i]=$year_and_month;
        break;
      case($i<223): 
        $year_and_month->year=17;
        $year_and_month->month=$i-210;
        $month_lookup[$i]=$year_and_month;
        break;
      default: //19: leap year
        $year_and_month->year=18;
        $year_and_month->month=$i-222;
        $month_lookup[$i]=$year_and_month;
        break;
    }
  }
}

/**
 * 
 * @param $molad_number - int The number of this molad counting from tishrei of year 1
 */
function find_the_year_and_month($molad_number){
  global $target_day, $target_hours, $target_chalakim, $month_lookup;
  // var_dump ($month_lookup);
  $machzorim = intdiv($molad_number, MONTHS_PER_MACHZOR);
  $months = $molad_number % MONTHS_PER_MACHZOR;
  $machzor_years = $machzorim * YEARS_PER_MACHZOR;
  $return_val = new stdClass();
  $return_val->year = $machzor_years + $month_lookup[$months]->year;
  $return_val->month = $month_lookup[$months]->month;
  return $return_val;
}


main();
?>