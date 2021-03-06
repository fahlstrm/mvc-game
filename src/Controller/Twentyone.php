<?php

declare(strict_types=1);

namespace Mos\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Frah\DiceGame\Game;

use function Mos\Functions\renderView;

class TwentyOne
{
    public function start(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $callable = new Game();

        $data = [
            "header" => "Spelet 21",
            "message" => "Välj antal tärningar",
            "playerScore" => $callable->getPlayerScore(),
            "computerScore" => $callable->getComputerScore()
        ];
        $callable->startGame();

        $_SESSION["object"] = $callable;
        $_SESSION["continue"] = "play";
        $_SESSION["newGame"] = null;

        $body = renderView("layout/diceGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function play(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $_SESSION["amount"] = $_POST["amount"] ?? null;
        $_SESSION["newGame"] = null;
        $callable = $_SESSION["object"];
        $callable->createDices(intval($_SESSION["amount"]));
        $callable->data = [
            "header" => "Kom igen då!",
            "message" => "Tryck på fortsätt om du vill fortsätta"
        ];

        $_SESSION["continue"] = "ongoing";

        $data = $callable->playGamePlayer($callable->data);

        $body = renderView("layout/diceGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function continue(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        $result = null;
        // var_dump($_POST)
        if (isset($_POST["ongoing"])) {
            $result = $_SESSION["object"]->playGamePlayer($_SESSION["object"]->data);
        } else if (isset($_POST["stop"])) {
            $result = $_SESSION["object"]->playGameComputer($_SESSION["object"]->data);
        } else if (!isset($_POST["ongoing"]) && !isset($_POST["stop"])) {
            $result = [];
        }

        $body = renderView("layout/diceGame.php", $result);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }



    public function reset(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $data = [
            "header" => "Spelet 21",
            "message" => "Välj antal tärningar",
        ];
        $_SESSION["object"]->resetScore();
        // $_SESSION["reset"];
        $data["playerScore"] = 0;
        $data["computerScore"] = 0;

        $body = renderView("layout/diceGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
