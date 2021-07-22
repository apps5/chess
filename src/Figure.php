<?php

class Figure {
    protected $isBlack;

    public function __construct($isBlack) {
        $this->isBlack = $isBlack;
    }

    /** @noinspection PhpToStringReturnInspection */
    public function __toString() {
        throw new \Exception("Not implemented");
    }
    
    public function isBlack(){
      return $this->isBlack ? true : false;
    }
    
    public function getFigureName(){
      return get_called_class();
    }
}
