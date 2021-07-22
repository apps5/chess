<?php

class Desk {
    private $figures = [];
    private $currentColor;

    public function __construct() {
        $this->figures['a'][1] = new Rook(false);
        $this->figures['b'][1] = new Knight(false);
        $this->figures['c'][1] = new Bishop(false);
        $this->figures['d'][1] = new Queen(false);
        $this->figures['e'][1] = new King(false);
        $this->figures['f'][1] = new Bishop(false);
        $this->figures['g'][1] = new Knight(false);
        $this->figures['h'][1] = new Rook(false);

        $this->figures['a'][2] = new Pawn(false);
        $this->figures['b'][2] = new Pawn(false);
        $this->figures['c'][2] = new Pawn(false);
        $this->figures['d'][2] = new Pawn(false);
        $this->figures['e'][2] = new Pawn(false);
        $this->figures['f'][2] = new Pawn(false);
        $this->figures['g'][2] = new Pawn(false);
        $this->figures['h'][2] = new Pawn(false);

        $this->figures['a'][7] = new Pawn(true);
        $this->figures['b'][7] = new Pawn(true);
        $this->figures['c'][7] = new Pawn(true);
        $this->figures['d'][7] = new Pawn(true);
        $this->figures['e'][7] = new Pawn(true);
        $this->figures['f'][7] = new Pawn(true);
        $this->figures['g'][7] = new Pawn(true);
        $this->figures['h'][7] = new Pawn(true);

        $this->figures['a'][8] = new Rook(true);
        $this->figures['b'][8] = new Knight(true);
        $this->figures['c'][8] = new Bishop(true);
        $this->figures['d'][8] = new Queen(true);
        $this->figures['e'][8] = new King(true);
        $this->figures['f'][8] = new Bishop(true);
        $this->figures['g'][8] = new Knight(true);
        $this->figures['h'][8] = new Rook(true);
        
        $this->setCurrentColor();        
    }

    public function move($move) {
        if (!preg_match('/^([a-h])(\d)-([a-h])(\d)$/', $move, $match)) {
            throw new \Exception("Incorrect move");
        }

        $xFrom = $match[1];
        $yFrom = $match[2];
        $xTo   = $match[3];
        $yTo   = $match[4];
        
        if(!$this->checkSequenceMove($xFrom, $yFrom)){         
          throw new \Exception("Incorrect sequence move...");      
        }     
        if(!$this->checkValidMove($xFrom, $yFrom, $xTo, $yTo)){         
          throw new \Exception("Incorrect piece move...");      
        } 

        if (isset($this->figures[$xFrom][$yFrom])) {
            $this->figures[$xTo][$yTo] = $this->figures[$xFrom][$yFrom];
        }
        unset($this->figures[$xFrom][$yFrom]);
        
        $this->setCurrentColor(); 
    }

    public function dump() {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }
    
    public function setCurrentColor() {
      if($this->currentColor == 'white'){
        $this->currentColor = 'black';
      } else {
        $this->currentColor = 'white';
      }
      return $this->currentColor;
    }
        
    public function checkValidMove($xFrom, $yFrom, $xTo, $yTo){  
      $indexMove = 1;
      if($this->currentColor == 'black'){
        $indexMove = -1;
      } 
      $figureName = $this->getFigureName($xFrom, $yFrom);
      if($figureName == 'Pawn'){
        return $this->checkValidMovePawn($xFrom, $yFrom, $xTo, $yTo, $indexMove);
      }
      return true;
    }
    
    public function checkValidMovePawn($xFrom, $yFrom, $xTo, $yTo, $indexMove){
      if(abs($yTo - $yFrom)/($yTo - $yFrom) != $indexMove){
        return false;
      }
      if(($indexMove * ($yTo - $yFrom) == 1) && ($xTo == $xFrom) && empty($this->figures[$xTo][$yTo])){
        return true;
      }
      if (($indexMove == 1) && ($yFrom == 2) && ($yTo == 4) && ($xTo == $xFrom) && empty($this->figures[$xTo][3]) && empty($this->figures[$xTo][4])){
        return true;
      }
      if (($indexMove == -1) && ($yFrom == 7) && ($yTo == 5) && ($xTo == $xFrom) && empty($this->figures[$xTo][6]) && empty($this->figures[$xTo][5])){
        return true;
      }    
      if ($indexMove == 1){                  
        if ((($xFrom == chr(ord($xTo)-1) && $yFrom == $yTo-1) || ($xFrom == chr(ord($xTo)+1) && $yFrom == $yTo-1)) && isset($this->figures[$xTo][$yTo])){
          $figureAttack = $this->figures[$xTo][$yTo];        
          if($figureAttack->isBlack()){
            unset($this->figures[$xTo][$yTo]);             
            return true;
          }
        }
      }
      if ($indexMove == -1){          
        if ((($xFrom == chr(ord($xTo)-1) && $yFrom == $yTo+1) || ($xFrom == chr(ord($xTo)+1) && $yFrom == $yTo+1)) && isset($this->figures[$xTo][$yTo])){
          $figureAttack = $this->figures[$xTo][$yTo];
          if(!$figureAttack->isBlack()){            
            unset($this->figures[$xTo][$yTo]);   
            return true;
          }
        }
      }  
      return false;
    }
      
    public function getFigureName($xFrom, $yFrom){
      $figure = $this->figures[$xFrom][$yFrom];
      if($figure){
        return $figure->getFigureName();
      } else {
        throw new \Exception("Empty figure..."); 
      }
    }
    
    public function checkSequenceMove($xFrom, $yFrom){
      $figure = $this->figures[$xFrom][$yFrom];      
      if($figure){
        if($this->currentColor == 'white' && !$figure->isBlack()){
          return true;
        } else if($this->currentColor == 'black' && $figure->isBlack()){
          return true;
        } else {
          return false;
        }
      } else {
        throw new \Exception("Empty figure..."); 
      }
    }
}
