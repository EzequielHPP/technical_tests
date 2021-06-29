<?php
include "../boot.php";

function whoIsWinner($piecesPositionList): string
{
    $board = [];
    foreach (range('A', 'G') as $letter) {
        $board[$letter] = [];
    }

    //Play
    foreach ($piecesPositionList as $play) {
        preg_match('/([A-G]){1}_(.*)/', $play, $matches);
        for ($index = 5;$index > count($board[$matches[1]])-1;$index--) {
            drawBoard($board, null, null, $matches[1], $matches[2], $index);
        }
        $board[$matches[1]][] = $matches[2];
        drawBoard($board);
        if (isWinner($board, $matches[1], $matches[2])) {
            return $matches[2];
        }
    }
    return 'Draw';
}

function drawBoard(
    $board,
    $checkingC = null,
    $checkingI = null,
    $fallingcolumn = null,
    $fallingColor = null,
    $lastIndex = null
) {
    ob_start();
    for ($x = 1; $x < 500; $x++) {
        print chr(27) . "[H" . chr(27) . "[2J";
    }
    for ($lines = 5; $lines >= 0; $lines--) {
        $white = "\033[37m";
        print $white . '||';
        foreach (range('A', 'G') as $letter) {
            $left = $checkingC === $letter && $lines === $checkingI ? '(' : ' ';
            $right = $checkingC === $letter && $lines === $checkingI ? ')' : ' ';
            if (array_key_exists($lines, $board[$letter]) || ($lastIndex === $lines && $fallingcolumn === $letter)) {
                $color = ($lastIndex === $lines && $fallingcolumn === $letter) ? $fallingColor : $board[$letter][$lines];
                switch (strtolower($color)) {
                    case "red":
                        $prefx = "\033[31m";
                        break;
                    case "yellow":
                        $prefx = "\033[33m";
                        break;
                }
                print $left . $prefx . '⚽' . $white . $right . '|';
            } else {
                print $left . ' ' . $right . '|';
            }
        }
        print '|' . "\n";
    }
    print '||';
    foreach (range('A', 'G') as $letter) {
        print '===|';
    }
    print '|' . "\n";
    $drawing = ob_get_contents();
    ob_end_clean();
    print $drawing;
    usleep($checkingC !== null || $lastIndex !== null ? 50000 : 2000000);
}

function isWinner($board, $column, $piece)
{
    $return = horizontalCheck($board, $column, $piece);
    if (!$return) {
        $return = verticalCheck($board, $column, $piece);
    }
    if (!$return) {
        $return = diagonalCheck($board, $column, $piece);
    }
    return $return;
}

function horizontalCheck($board, $column, $piece)
{
    $firstColumn = ord('A');
    $lastColumn = ord('G');

    // Check Horizontally
    $index = count($board[$column]) - 1;
    $ascii = ord($column);
    $inARow = 0;
    $firstCheck = chr(($ascii - 3 < $firstColumn ? $firstColumn : $ascii - 3));
    $lastCheck = chr(($ascii + 3 > $lastColumn ? $lastColumn : $ascii + 3));
    foreach (range($firstCheck, $lastCheck) as $letter) {
        drawBoard($board, $letter, $index);
        if (array_key_exists($index, $board[$letter]) && $board[$letter][$index] === $piece) {
            ++$inARow;
        } elseif ($inARow > 0) {
            $inARow = 0;
        }
        if ($inARow === 4) {
            return true;
        }
    }
    return false;
}

function verticalCheck($board, $column, $piece)
{
    $index = count($board[$column]) - 1;
    if ($index < 3) {
        return false;
    }
    $totalMatches = 0;
    for ($x = $index; $x >= 0; $x--) {
        drawBoard($board, $column, $x);
        if ($board[$column][$x] === $piece) {
            $totalMatches++;
        } else {
            $totalMatches = 0;
        }
    }
    return $totalMatches >= 4;
}

function diagonalCheck($board, $column, $piece)
{
    $firstColumn = ord('A');
    $lastColumn = ord('G');

    // Check Horizontally
    $index = count($board[$column]) - 1;
    $ascii = ord($column);
    $inARow = 0;
    $firstCheck = chr(($ascii - 3 < $firstColumn ? $firstColumn : $ascii - 3));
    $lastCheck = chr(($ascii + 3 > $lastColumn ? $lastColumn : $ascii + 3));
    foreach (range($firstCheck, $lastCheck) as $letter) {
        $differenceIndex = $index + (ord($column) - ord($letter));
        performCheckDiagonaly($board, $letter, $differenceIndex, $index, $piece, $inARow);
        if ($inARow === 4) {
            return true;
        }
    }
    $inARow = 0;
    foreach (range($firstCheck, $lastCheck) as $letter) {
        $differenceIndex = $index + ord($letter) - ord($column);
        performCheckDiagonaly($board, $letter, $differenceIndex, $index, $piece, $inARow);
        if ($inARow === 4) {
            return true;
        }
    }
    return false;
}

function performCheckDiagonaly($board, $letter, $differenceIndex, $index, $piece, &$inARow)
{
    if ($differenceIndex >= 0) {
        drawBoard($board, $letter, $differenceIndex);
        if (array_key_exists($differenceIndex, $board[$letter]) && $board[$letter][$differenceIndex] === $piece) {
            ++$inARow;
        } elseif ($inARow > 0) {
            $inARow = 0;
        }
    }
}

$test = (new \App\Test());
$test->assertEquals(whoIsWinner([
    "C_Yellow",
    "E_Red",
    "G_Yellow",
    "B_Red",
    "D_Yellow",
    "B_Red",
    "B_Yellow",
    "G_Red",
    "C_Yellow",
    "C_Red",
    "D_Yellow",
    "F_Red",
    "E_Yellow",
    "A_Red",
    "A_Yellow",
    "G_Red",
    "A_Yellow",
    "F_Red",
    "F_Yellow",
    "D_Red",
    "B_Yellow",
    "E_Red",
    "D_Yellow",
    "A_Red",
    "G_Yellow",
    "D_Red",
    "D_Yellow",
    "C_Red"
]),
    "Yellow"
);
sleep(5);
$test->assertEquals(whoIsWinner([
    "C_Yellow",
    "B_Red",
    "B_Yellow",
    "E_Red",
    "D_Yellow",
    "G_Red",
    "B_Yellow",
    "G_Red",
    "E_Yellow",
    "A_Red",
    "G_Yellow",
    "C_Red",
    "A_Yellow",
    "A_Red",
    "D_Yellow",
    "B_Red",
    "G_Yellow",
    "A_Red",
    "F_Yellow",
    "B_Red",
    "D_Yellow",
    "A_Red",
    "F_Yellow",
    "F_Red",
    "B_Yellow",
    "F_Red",
    "F_Yellow",
    "G_Red",
    "A_Yellow",
    "F_Red",
    "C_Yellow",
    "C_Red",
    "G_Yellow",
    "C_Red",
    "D_Yellow",
    "D_Red",
    "E_Yellow",
    "D_Red",
    "E_Yellow",
    "C_Red",
    "E_Yellow",
    "E_Red"
]),
    "Yellow"
);
sleep(5);
$test->assertEquals(whoIsWinner([
    "F_Yellow",
    "G_Red",
    "D_Yellow",
    "C_Red",
    "A_Yellow",
    "A_Red",
    "E_Yellow",
    "D_Red",
    "D_Yellow",
    "F_Red",
    "B_Yellow",
    "E_Red",
    "C_Yellow",
    "D_Red",
    "F_Yellow",
    "D_Red",
    "D_Yellow",
    "F_Red",
    "G_Yellow",
    "C_Red",
    "F_Yellow",
    "E_Red",
    "A_Yellow",
    "A_Red",
    "C_Yellow",
    "B_Red",
    "E_Yellow",
    "C_Red",
    "E_Yellow",
    "G_Red",
    "A_Yellow",
    "A_Red",
    "G_Yellow",
    "C_Red",
    "B_Yellow",
    "E_Red",
    "F_Yellow",
    "G_Red",
    "G_Yellow",
    "B_Red",
    "B_Yellow",
    "B_Red"
]),
    "Red"
);
sleep(5);
$test->assertEquals(whoIsWinner([
    "A_Yellow",
    "B_Red",
    "B_Yellow",
    "C_Red",
    "G_Yellow",
    "C_Red",
    "C_Yellow",
    "D_Red",
    "G_Yellow",
    "D_Red",
    "G_Yellow",
    "D_Red",
    "F_Yellow",
    "E_Red",
    "D_Yellow"
]),
    "Red"
);
sleep(5);
$test->assertEquals(whoIsWinner([
    "A_Red",
    "B_Yellow",
    "A_Red",
    "B_Yellow",
    "A_Red",
    "B_Yellow",
    "G_Red",
    "B_Yellow"
]),
    "Yellow"
);
sleep(5);
$test->assertEquals(whoIsWinner([
    "A_Red",
    "B_Yellow",
    "A_Red",
    "E_Yellow",
    "F_Red",
    "G_Yellow",
    "A_Red",
    "G_Yellow"
]),
    "Draw"
);
sleep(5);
