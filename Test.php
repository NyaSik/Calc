<?php

class MyCalc {
    private $AExpression = '';
    private $rPos =0;
    private $rPosMax = 0;
    private $BracketsCount = 0;
    private $NextChar = '';
    public $Result;


    public function Math($Math) {
        $n = new MyCalc($Math);
        return $n->Result;
    }

    function SkipEmpty($r = false) {
        while((empty($this->NextChar) && !is_numeric($this->NextChar))
          or (!preg_match('/[0-9,.\-+\/*()<>=]/', $this->NextChar, $v) && $r)) {
           $this->NextChar();
        }
    }

    function IsNoChar() {
        if(preg_match('/[+-\/*<>=]/', $this->NextChar, $bb)) {
            if(($bb[0] == '=') or ($bb[0] == '*')) return true;

            throw new Exception(sprintf('Повторяющиеся знак в позиции: %d', $this->rPos));
            return false;
        }
        return true;
    }

    public function __construct($SWP_AExpression) {
        $this->AExpression = trim($SWP_AExpression);
        $this->rPos = 0;
        $this->BracketsCount = 0;
        $this->rPosMax = strlen($this->AExpression);

        if($this->rPosMax == 0) {
           throw new Exception('Строка Выражение пустая');
        }
        $this->Result = $this->Expression();
    }

    public function NextChar() {
        $this->NextChar = trim(
            ($this->rPos <= $this->rPosMax)
            ? $this->AExpression[$this->rPos]
            : '\0'
        );

        if($this->NextChar == '\0') return;

        $this->rPos ++;
        $this->SkipEmpty(true);
    }

    public function NextToValue() {
        $result = '';
        $PointDepth = 0;

        $this->SkipEmpty();

        if(($this->NextChar >= '0') && ($this->NextChar <= '9')) {
            while(($this->NextChar >= '0') &&($this->NextChar <= '9')) {
                $result .= $this->NextChar;
                $this->NextChar();

                if(($this->NextChar == '.') ||($this->NextChar == ',')) {
                    $result .= $this->NextChar;
                    $this->NextChar();

                    $value = trim($this->NextChar);
                    while((empty($this->NextChar) && !is_numeric($this->NextChar))
                    or (!preg_match('/[0-9,.\-+\/*()<>=]/', $this->NextChar, $v))) {
                        $result .= $this->NextChar;
                        $this->NextChar();
                    }
                }
            }
        } else {
            switch($this->NextChar) {
                case "-":
                    $this->NextChar();
                    $result -= $result;
                break;
                case "(":
                    $this->BracketsCount ++;
                    $this->NextChar();
                    $result = $this->Expression();
                    $this->SkipEmpty();
                if($this->NextChar != ')') {
                    $result = 0;

                    throw new Exception('Правые скобки не найдены');
                } else {
                    $this->NextChar();
                }
                break;
            }
        }
        if($this->NextChar == ')')
        {
            $this->BracketsCount --;
            if($this->BracketsCount < 0)
                throw new Exception(sprintf('Правая скобка не имеет левой позиции: %d', $this->rPos ));
        }
        return $result;
    }


    public function MDiv() {
        $result = $this->NextToValue();

        while(true) {
            switch($this->NextChar) {
                case "*":
                    $this->NextChar();
                    if($this->NextChar == '*') {
                       $this->NextChar();
                       $result = pow($result, $this->IsNoChar() ? $this->NextToValue() : 0);
                    } else
                        $result *= $this->IsNoChar() ? $this->NextToValue() : 0;
                break;
                  case "/":
                    $this->NextChar();
                    $Denominator = $this->IsNoChar() ? $this->NextToValue() : 0;
                    if($Denominator <> 0) {
                       $result /= $Denominator;
                    } else {
                        throw new Exception('Деление на 0');
                    }

                break;
                default:
                    return $result;
            }
        }
    }
    public function Expression() {
        $result = $this->MDiv();
        while(true) {
            switch($this->NextChar) {
                case "+":
                    $this->NextChar();
                    $result += $this->IsNoChar() ?  $this->MDiv() : 0;

                break;
                case "-":
                    $this->NextChar();
                    $result -=  $this->IsNoChar() ? $this->MDiv() : 0;
                break;
                case "<":
                    $this->NextChar();
                    if($this->NextChar == '=') {
                       $this->NextChar();
                        $result = $this->IsNoChar() ? ($result <= $this->MDiv()) : 0;
                    } else {
                        $result = $this->IsNoChar() ? ($result < $this->MDiv()) : 0;
                    }
                break;
                case ">":
                    $this->NextChar();
                    if($this->NextChar == '=') {
                       $this->NextChar();
                        $result = $this->IsNoChar() ? ($result >= $this->MDiv()) : 0;
                    } else {
                        $result = $this->IsNoChar() ? ($result > $this->MDiv()) : 0;
                    }
                break;
                default:
                    return $result;
            }
        }
    }
}  
