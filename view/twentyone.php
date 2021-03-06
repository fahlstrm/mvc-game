<?php

/**
 * View to play Twentyone!
 */

declare(strict_types=1);

use function Mos\Functions\url;

$header = $header ?? null;
$message = $message ?? null;
$player = $player ?? null;
$playersum = $playersum ?? null;

?>
<div class="container">
  <div class="left">
    <h1><?= $header ?></h1>

    <p><?= $message ?></p>
    <?php if (!isset($_SESSION["amount"]) && (!isset($playerResult)) || (isset($_SESSION["newGame"]))) { ?>
    <form action="<?= url("/dice/play") ?>" method="post">
      <label for="fname">Antal tärningar:</label>
      <input type="number" name="amount" min="1" max="2" value="1"><br><br>
      <button type="submit">Starta</button>
    </form>

    <?php } else if (isset($_SESSION["amount"])) { ?>
          <p> Detta slag: <?= $player ?> </p>
          <p> Total summa: <?= $playersum ?> </p>

          <br><br>
          <form action="../dice/continue" method="post">
          <button type="submit" name="ongoing">Fortsätt</button>
          <button type="submit" name="stop">Stopp</button>
          </form>

    <?php } ?>
  </div>
  <div class="right">
      <h1> Scoreboard: </h1>
    <?php if (isset($computersum)) { ?> 
      <h3> Denna omgång: </h3>
      <p> Ditt resultat <?= $playersum ?> </p>
      <p> Datorns resultat: <?= $computersum ?> </p>


    <?php } if (isset($gameover)) { ?>
          <h1> <?= $winner ?>
    <?php } ?>
    <h3> Total ställning: </h3>
    <p> Spelare: <?= $playerScore ?? null ?> </p>
    <p> Datorn: <?=  $computerScore ?? null ?> </p>
     
    <?php if (isset($gameover)) { ?>
        <form action="../dice/reset" method="post">
          <button type="submit" name="reset">Nollställ Score</button>
        </form>
    <?php } ?>
  </div>
</div>
