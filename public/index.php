<?php

require '../vendor/autoload.php';

use App\Arena;
use App\Grass;
use App\Shield;
use App\Weapon;
use App\Hero;
use App\Bush;
use App\Hind;
use App\Monster;
use App\Water;

session_start();

if (!empty($_GET['reset'])) {
    unset($_SESSION['arena']);
}

$arena = $_SESSION['arena'] ?? null;

/** initialisation **/
if (!$arena instanceof Arena) {
    $heracles = new Hero('Heracles', 0, 0);
    $hind = new Monster('Ceryneian Hind', 9, 6);
    $hind->setImage('hind.svg');
    
    $sword = new Weapon(10);
    $heracles->setWeapon($sword);
    $shield = new Shield();
    $heracles->setShield($shield);

    $tiles = $elements = [];
    $waters = $bushes = $grasses = [];
    if (class_exists(Grass::class)) {
        $grasses = [
            new Grass(0, 0),
            new Grass(1, 0),
            new Grass(8, 0),
            new Grass(9, 0),
            new Grass(0, 1),
            new Grass(1, 1),
            new Grass(8, 1),
            new Grass(9, 1),
            new Grass(0, 2),
            new Grass(1, 2),
            new Grass(7, 2),
            new Grass(8, 2),
            new Grass(0, 3),
            new Grass(1, 3),
            new Grass(2, 3),
            new Grass(3, 3),
            new Grass(4, 3),
            new Grass(7, 3),
            new Grass(8, 3),
            new Grass(0, 4),
            new Grass(1, 4),
            new Grass(0, 5),
            new Grass(0, 6),
        ];
    }
    if (class_exists(Water::class)) {
        $waters = [
            new Water(2, 0),
            new Water(3, 0),
            new Water(4, 0),
            new Water(5, 0),
            new Water(6, 0), 
            new Water(7, 0), 
            new Water(2, 1),
            new Water(3, 1),
            new Water(4, 1),
            new Water(5, 1),
            new Water(6, 1),
            new Water(7, 1),
            new Water(3, 1),
            new Water(4, 1),
            new Water(3, 2),
            new Water(4, 2),
            new Water(5, 2),
            new Water(6, 2),
            new Water(5, 3),
            new Water(6, 3),
            new Water(6, 4),
            new Water(6, 5),
            new Water(7, 5),
            new Water(7, 6),
            new Water(0, 8),
            new Water(1, 8),
            new Water(0, 7),
            new Water(0, 9),
            new Water(1, 9),
            new Water(2, 9),
            new Water(3, 9),
            new Water(4, 9),
            new Water(5, 9),
            new Water(7, 4),
            new Water(8, 4),
            new Water(8, 4),
            new Water(8, 5),
            new Water(9, 5),

        ];
    }
    if (class_exists(Bush::class)) {
        $bushes = [
            new Bush(2, 2),
            new Bush(4, 4),
            new Bush(3, 7),
            new Bush(2, 5),
            new Bush(6, 9),
            new Bush(7, 9),
            new Bush(7, 8),
            new Bush(7, 7),
            new Bush(6, 8),
            new Bush(9, 2),
            new Bush(9, 3),
        ];
    }

    $tiles = [...$waters, ...$grasses, ...$bushes];

    $arena = new Arena($heracles, [$hind], $tiles);
}

$_SESSION['arena'] = $arena;

try {
    if (!empty($_GET['move'])) {
        if(method_exists($arena, 'arenaMove')) {
            $arena->arenaMove($_GET['move']);
        } else {
            $arena->move($arena->getHero(), $_GET['move']);
        } 
    }
    if (isset($_GET['fight']) && method_exists($arena, 'battle')) {
        $arena->battle($_GET['fight']);
    }  

} catch (Exception $exception) {
    $error = $exception->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heracles Labours #5</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <h1>Heracles vs Ceryneian Hind</h1>
        <a class="btn reset" href="?reset=reset">Reset</a>
    </header>
    <main>
        <div class="error"><?= $error ?? ''; ?></div>
        <div class="fighters">
            <a href="#hero">
                <div class="fighter">
                    <figure class="heracles">
                        <img src="<?= $arena->getHero()->getImage() ?>" alt="heracles">
                        <figcaption><?= $arena->getHero()->getName() ?></figcaption>
                    </figure>
                    <progress class="life" max="100" value="<?= $arena->getHero()->getLife() ?>"></progress>
                </div>
            </a>
            <?php foreach ($arena->getMonsters() as $monster) : ?>
                <div class="fighter">
                    <figure class="monster">
                        <img src="<?= $monster->getImage() ?>" alt="monster">
                        <figcaption><?= $monster->getName() . '(' . $monster->getLife() . ')' ?></figcaption>
                    </figure>
                    <progress class="life" max="100" value="<?= $monster->getLife() ?>"></progress>
                </div>
            <?php endforeach; ?>
        </div>


        <?php include 'map.php' ?>
    </main>

    <?php include 'inventory.php' ?>
    <script src="/assets/js/move.js"></script>
</body>

</html>
