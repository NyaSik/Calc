# Calc


$List = array();

$List[] = '2+4';

$List[] = '10 - 3 + ((10 - 3) + 10) ** 2';

$List[] = '2+(4*4)';

$List[] = '(2+4)*4';

$List[] = '(454+(43+(5*34+987*(654+3453.434565))-2))*22 - 300';

$List[] = '10 ** 2';

$List[] = '(10 ** 2) * 2 ** 2';

foreach($List as $v) {

    echo $v . ' = ' . MyCalc::Math($v) . PHP_EOL;
    
}  


---------------------------

$List = array();

$List[] = '52 < 55';

$List[] = '65 <= 35';

$List[] = '35 > 45';

$List[] = '55 >= 35';

$List[] = '(6 >= 4) + 4 + (3 < 5)';

$List[] = '((6 >= 4) + 4 + (3 < 5) >= 6)';

foreach($List as $v) {

    echo $v . ' = ' . MyCalc::Math($v) . PHP_EOL;
    
}  
