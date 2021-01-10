<?php

namespace App\Entity;

class CoinFlipper {

  public $id;
  public $flippedTails;

  public function __construct() {
    $this->flippedTails = FALSE;
  }

  // Return random, 0 or 1.
  // 0 = Heads
  // 1 = Tails
  public function flipCoin() {
    $flip = rand(0, 1);
    if ($flip === 1) {
      $this->flippedTails = TRUE;
    }
  }

}