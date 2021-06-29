Having had a restless nights sleep, you’ve woken up in a daze and have found yourself in Westeros. Confused, the sun is just beginning to rise and yet in the distance, you can hear crowds jeering, roaring battle cries and a stampede of horses charging into what seems like a long-awaited battle.

Suddenly, you realise what’s happening. The time has finally come. The Seven Kingdoms are at war and with one common goal; to defeat the White Walkers and the dreaded Night King who leads them.

At this point, sides do not matter and you know what you need to do. Your mission? To calculate the chances of the Seven Kingdoms defeating the White Walkers, sending them back beyond the wall and to sleep beneath the ice once more.

You’ll need to base your calculation on the following facts:

Seven Kingdom Army:
- {no_of_dragons} Dragons, each one has 600 damage and 600 defence
- 5000 Infantry, each one has 2 damage and 2 defence
  White Walker Army:
- {no_of_white_lords} White Lords, each one has 50 damage and 100 defence
- 10000 Walkers Infantry, each one has 1 damage and 3 defence
  The attacker cannot have passive units (units which are not attacking in one turn)
  One unit cannot remain with unconsumed damage as long as there are still enemies remaining
  Each attacking unit can strike multiple defenders in the same turn until its damage is consumed
  When an attacking unit is striking, its damage is reduced in the current turn with the defense value of the defender
  The turn ends when the cumulated damage of the attacker reaches 0
  After an attack, the defenders which are not killed (defence > 0) will replenish their defence to 100%
  Find the fastest way to victory, no matter which team wins
  Example: A Dragon (600 damage) attacks a White Lord (defence 100)

The Dragon has 500 damage remaining and can strike 5 more White Lords in the same turn
The White Lord dies
How long will the battle last (how many turns) and who will emerge victorious?

INPUT
string first_strike_army_name
int no_of_dragons
int no_of_white_lords

OUTPUT
string result (formatted as army_name|turns)

EXAMPLE 1
Input: "Seven Kingdom Army", 4, 5
Output: White Walker Army|6

EXAMPLE 2
Input: "Seven Kingdom Army", -1, 5
Output: Invalid parameter provided
