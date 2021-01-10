<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\CoinFlipper;

class FlipTestController {

  protected $results = [];

  public function createPlayers() {
    // Create 1000 players.
    for ($i = 0; $i < 1000; $i++) {
      $player = new CoinFlipper();
      $player->id = $i;
      $this->results['standing'][] = $player;
    }
  }

  public function runRound() {
    foreach ($this->results['standing'] as $standing_player) {
      $id = $standing_player->id;
      $standing_player->flipCoin();
      if ($standing_player->flippedTails) {
        unset($this->results['standing'][$id]);
        $this->results['seated'][] = $standing_player;
      }
    }
  }

  public function runTestMultiple() {
    $response = '<table>';
    $response .= '<tr>';
    $response .= '<th>Lone Survivors</th>';
    $response .= '<th>Other Results</th>';
    $response .= '<th>Chance of Lone Survivor</th>';
    $response .= '</tr>';

    $lone_survivors = 0;
    $other = 0;

    $response .= '<tr>';

    for ($i = 0; $i < 1000000; $i++) {
      $has_lone_survivor = $this->runTest();
      if ($has_lone_survivor) {
        $lone_survivors++;
      }
      else {
        $other++;
      }
      // Reset results before going to next experiment.
      $this->results = [];
    }

    $total = $lone_survivors + $other;
    $lone_percent = ($lone_survivors / $total) * 100;

    $response .= '<td>' . $lone_survivors . '</td>';
    $response .= '<td>' . $other . '</td>';
    $response .= '<td>' . $lone_percent . '%</td>';
    $response .= '<tr>';
    $response .= '</table>';

    return new Response($response);
  }

  public function runTest() {
    // Create players.
    $this->createPlayers();
    $response = '<table>';
    $response .= '<tr>';
    $response .= '<th>Round</th>';
    $response .= '<th>Standing</th>';
    $response .= '<th>Seated</th>';
    $response .= '</tr>';

    for ($i = 1; $i < 11; $i++) {
      $response .= '<tr>';
      $response .= '<td>'. $i . '</td>';
      $this->runRound();
      $standing = count($this->results['standing']);
      $seated = count($this->results['seated']);
      $response .= '<td>' . $standing . '</td>';
      $response .= '<td>' . $seated . '</td>';
      $response .= '</tr>';
    }

    $response .= '</table>';

    if ($standing === 1) {
      return TRUE;
    }

    return FALSE;
    // return new Response($response);
  }

}