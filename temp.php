<?php

/**
 * A simple PHP MVC skeleton
 *
 * @package php-mvc
 * @author Panique
 * @link http://www.php-mvc.net
 * @link https://github.com/panique/php-mvc/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// load the (optional) Composer auto-loader
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
}

require 'application/config/autoload.php';
require 'application/config/config.php';

// load application config (error reporting etc.)

$colName = ["First Name", "Last Name", "email", "phone", "age", "serial Number"];
$data = [
    ["ali", "mohammed", "ali.mohsssssssss123123124 125125125ammad", "", "15", "1245"],
    ["ali", "soso", "ali.soso", "", "16", "4245"],
    ["bashar", "rzk", "basharRezk124", "0975126126", "25", "4255"],
    ["mohammed", "ali", "ahmad.mohammad", "0925554365", "12", "6745"],
    ["mohammed", "ali", "ahmad.mohammad", "0925554365", "12", "6745"],
    ["ahmad", "mohammed", "asd.gag", "0925162365", "15", "8745"],
    ["sabri", "samer", "br.llllll", "0962556430", "19", "9765"],
    ["fahim", "7mar", "gabi.jahsh", "0921551265", "11", "1215"],
];
$curTotRow = 20;
$allRow = 1255;
$from = 1;
$to = 20;

$pfi = [
    new ProfileItem('PHOTO','profile',0,1),
    new ProfileItem('PHOTO','Second Profile',1,1),
    new ProfileItem('NAME','last name',2,0),
    new ProfileItem('PHONE','phone',1,0),
    new ProfileItem('EMAIL','email',0,2),
    new ProfileItem('PASSWORD','password',1,2),
    new ProfileItem('NAME','first name',0,0),
    new ProfileItem('TEXT','age',null,null),
    new ProfileItem('PHONE','mobile',null,null),
]; 

$questions = [
    new QuestionItem('45','3','hello',',mmmma\n sxmmmxaslx      \n \n as dasd ',
        new OptionItem('option1',2,0),
        new OptionItem('option512',3,0),   
        new OptionItem('opt6346',1,1)   
    ),
    new QuestionItem('12','1','hello','qg-0qwgp[kqwg',
        new OptionItem('qwr \n \n \n  qwer qw',1,0),
        new OptionItem('qwr qwer qafsfsw',5,1),   
        new OptionItem('qwr qwerqwf qw',2,0),   
        new OptionItem('qw',3,0),  
        new OptionItem('qw qw',4,0)   
    ),
    new QuestionItem('51','2','hello',';lih2q3oq wd[p qwd[ioph qwd',
        new OptionItem('q1239125',5,0),   
        new OptionItem('asfoj] ',2,0),   
        new OptionItem('asfasf',3,1),  
        new OptionItem('23p[ti12',1,0)   
    ),
];

$correctAnswer = [
    new CorrectAnswerItem( 114,'','125',26,100),
    new CorrectAnswerItem( 115,'','3',64,100),
    new CorrectAnswerItem( 116,'','1',90,100),
    ];

$duration = 60;//min
$startD = "2022-04-16 9:40:11";

session_unset();
$_SESSION['user_name'] = 'mullham';
$_SESSION['role_title'] = 'test_center_admin';
$_SESSION['test_center_name'] = 'TC5';
$_SESSION['photo_link'] = URL.'storage/img/downlaod.png';

$_GET['url']='';

if(!isset($_SESSION['user_name']))
    $_SESSION['user_name'] = null;
if(!isset($_SESSION['role_title']))
    $_SESSION['role_title'] = 'visitor';
if(!isset($_SESSION['test_center_name']))
    $_SESSION['test_center_name'] = null;
if(!isset($_SESSION['photo_link']))
    $_SESSION['photo_link'] = URL.'public/icon/person.svg';

// (new CoursesTableView())->render($colName,$data,$curTotRow,$allRow,$from,$to);
// (new SignupView())->render();
// (new ProfileDesignView())->render(...$pfi);
// (new ProfileView())->render(...$pfi);
// (new LoginView())->render();
//  (new TestView())->render($startD, $duration, $correctAnswer,61, ...$questions);
// (new GenerateTestView())->render( $correctAnswer, ...$questions);
// (new HomeView())->render();
// (new DashboardListView())->render();

//admin:
// home page
// make test + fill test description 
// start new season 
// add additional info 

//TestCenter:
// home page
// countDown + test details
// tables... 3 or 2

//Student: 
// reg courses
// courses
// tests... 

//notes:
// session inted of cookies
// 

