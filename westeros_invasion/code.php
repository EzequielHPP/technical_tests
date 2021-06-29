<?php
include "../boot.php";

class Army
{
    public $name;

    private $units = [
        'prime' => [],
        'sub' => []
    ];

    public function __construct(string $name, array $primeUnit, array $subUnits)
    {
        $this->name = $name;
        $this->createUnits('prime', $primeUnit);
        $this->createUnits('sub', $subUnits);
    }

    private function createUnits($unitCategory, $unit)
    {
        for ($unitX = 1; $unitX <= $unit['total']; $unitX++) {
            $this->units[$unitCategory][] = [
                'damage' => $unit['damage'],
                'round_damage' => $unit['damage'],
                'defence' => $unit['defence'],
                'health' => $unit['defence']
            ];
        }
    }

    /**
     * Attack a given enemy
     *
     * @param $enemy
     * @return bool
     */
    public function attack(&$enemy): bool
    {
        $enemy->resetHealth();
        if ($this->attackUsing('prime', $enemy)) {
            return true;
        }
        if ($this->attackUsing('sub', $enemy)) {
            return true;
        }

        return false;
    }

    private function attackUsing($unit, &$enemy): bool
    {
        $entries = array_values($this->units[$unit]);
        $health = 0;
        foreach ($entries as $entry) {
            $health += $entry['health'];
        }
        $totalUnitsAlive = count($this->units[$unit]);

        line(($unit === 'prime' ? '(◉ܫ◉)' : '(Ⳬ)') . 'x' . $totalUnitsAlive . ' [h: ' . ($totalUnitsAlive > 0 ? $health / count($this->units[$unit]) : 0) . ']');
        foreach ($this->units[$unit] as $index => $attackingUnit) {
            $attackDamage = $attackingUnit['damage'];
            while ($attackDamage > 0) {
                // If enemy couldn't defend (false) then we win (true)
                if (!$enemy->defend($attackDamage)) {
                    return true;
                }
            }
        }
        // If defender defended all the attacks then this turn was successful
        return false;
    }

    public function resetHealth()
    {
        foreach ($this->units['prime'] as $index => $unit) {
            $this->units['prime'][$index]['health'] = $this->units['prime'][$index]['defence'];
        }
        foreach ($this->units['sub'] as $index => $unit) {
            $this->units['sub'][$index]['health'] = $this->units['sub'][$index]['defence'];
        }
    }

    public function defend(&$attack): bool
    {
        if (count($this->units['prime']) > 0) {
            $this->defendUsing('prime', $attack);
        }
        if ($attack > 0) {
            if (count($this->units['sub']) > 0) {
                $this->defendUsing('sub', $attack);
            }
        }
        $availableUnits = count($this->units['prime']) + count($this->units['sub']);
        if ($availableUnits > 0) {
            return true;
        }

        return false;
    }

    private function defendUsing($unit, &$attack)
    {
        $deadUnits = [];
        foreach ($this->units[$unit] as $index => $defendingUnit) {
            $health = $defendingUnit['health'];
            if ($attack <= 0) {
                break;
            }
            $health -= $attack;
            $attack -= $defendingUnit['defence'];

            if ($health <= 0) {
                $deadUnits[] = $index;
            }

            $this->units[$unit][$index]['health'] = $health;
        }

        foreach ($deadUnits as $index) {
            unset($this->units[$unit][$index]);
        }
    }
}

function line($text, $skip = false)
{
    if(DEBUG === true) {
        echo $text . "\n";

        if (!$skip) {
            sleep(1);
        }
    }
}

function run($first_strike_army_name, $no_of_dragons, $no_of_white_lords)
{
    $armies = [
        'Seven Kingdom Army' => [
            ['total' => $no_of_dragons, 'damage' => 600, 'defence' => 600],
            ['total' => 5000, 'damage' => 2, 'defence' => 2]
        ],
        'White Walker Army' => [
            ['total' => $no_of_white_lords, 'damage' => 50, 'defence' => 100],
            ['total' => 10000, 'damage' => 1, 'defence' => 3]
        ]
    ];

    if ($no_of_dragons < 0 || $no_of_white_lords < 0 || !array_key_exists($first_strike_army_name, $armies)) {
        return 'Invalid parameter provided';
    }

    line("==================== WAR HAS STARTED ====================");

    // Prepare for battle
    foreach ($armies as $armyName => $value) {
        $armies[$armyName] = (new Army($armyName, $value[0], $value[1]));
    }

    // To battle
    $rounds = 0;
    $battleFinished = false;
    while ($battleFinished === false) {
        line("Round " . $rounds . '-------------------------------------');
        $rounds++;
        switch ($first_strike_army_name) {
            case 'Seven Kingdom Army':
                $attacker = 'Seven Kingdom Army';
                $defender = 'White Walker Army';
                break;
            default:
                $defender = 'Seven Kingdom Army';
                $attacker = 'White Walker Army';
                break;
        }

        line($armies[$attacker]->name . ' ⚔️' . $armies[$defender]->name);

        $battleFinished = $armies[$attacker]->attack($armies[$defender]);

        if (!$battleFinished) {
            if ($first_strike_army_name === 'Seven Kingdom Army') {
                $first_strike_army_name = 'White Walker Army';
            } else {
                $first_strike_army_name = 'Seven Kingdom Army';
            }
        }
    }

    line("War has ended with " . $first_strike_army_name . ' being the winner in '.$rounds.' rounds');

    return $first_strike_army_name . '|' . $rounds;
}

$tests = [
    [
        'param' => ['Seven Kingdom Army', 4, 1],
        'expected' => 'White Walker Army|6'
    ],
    [
        'param' => ['Seven Kingdom Army', 12, 5],
        'expected' => 'Seven Kingdom Army|5'
    ],
    [
        'param' => ['Seven Kingdom Army', 16, 0],
        'expected' => 'Seven Kingdom Army|3'
    ],
    [
        'param' => ['Seven Kingdom Army', 2, 6],
        'expected' => 'White Walker Army|4'
    ]
];
const DEBUG = false;

$testClass = (new \App\Test());
foreach ($tests as $test) {
    $testClass->assertEquals($test['expected'], run($test['param'][0], $test['param'][1], $test['param'][2]), 'FAILED: [//result//]');
}
