# Magic: the Gathering Game Simulator

Simulates games where decks can use only the following cards:

1. Bear: Bear Cub
2. Burn: Flame Jet without cycling
3. Removal: Defeat
4. Land

Decks are composed of these cards, for example:

```
$deckOne = new Deck(bear: 37, land: 23);
```

This creates a new deck with 37 Bears and 23 Lands.

## Notes

* Will mulligan up to 3 times, throwing hands back that have 1 land or 6/7 lands. Tries to optimally bottoms cards after mulligans.
* Creatures do not block at all.
* All spells are cast at sorcery speed.
* Maximum deck size is 250 cards
* Simulations ran 100,000 games. I didn't notice much different at 1M or higher. Only takes about 30 seconds to run 100k games.

## Results

### Bears vs. Removal

| Win-Rate %  | Bears  | Removal |
| ----------- | ------ | ------- |
| On the Play | 60.22% | 6.50%   |
| On the Draw | 93.50% | 39.79%  |

### Bears vs. Burn

| Win-Rate %  | Bears  | Burn   |
| ----------- | ------ | ------ |
| On the Play | 98.63% | 41.41% |
| On the Draw | 58.59% | 1.37%  |

### Bears vs. Bears

| Player   | On the Play | On the Draw |
| -------- | ----------- | ----------- |
| Bears    | 98.06%      | 1.94%       |

## Highest On the Draw Win-Rate

### Non-Bears vs. Bears

This could definitely use more research, but from my tests, adding Burn or Creatures
to the deck did not improve the Removal deck's on-the-draw win-rate.

What _did_ help was increasing the Removal cards and decreasing the lands.
The optimal build against 37 Bears is playing 42 Removal spells. This produces
a win-rate of `61.20%`, the highest I was able to achieve in all different
combinations of Bears, Removal, and Burn spells. Adding 5 extra Removal spells
against the 37 Bears seems to work okay given the mulligans.

### Bears vs. Bears

The highest win-rate on the play in this experiment is just Bears and Lands.
The on-the-draw Bears deck plays 37 Bears and 23 Lands.
Here is the on-the-play win-rate after 100,000 games:

| Lands | Win-rate |
| ----- | -------- |
| 15    | 96.02%   |
| 16    | 97.32%   |
| 17    | 97.99%   |
| 18    | 98.40%   |
| 19    | 98.64%   |
| 20    | 98.66%   |
| 21    | 98.58%   |
| 22    | 98.29%   |
| 23    | 98.00%   |
| 24    | 97.44%   |
| 25    | 96.88%   |
| 26    | 96.14%   |
| 27    | 94.97%   |
| 28    | 93.73%   |
| 29    | 92.47%   |
| 30    | 90.67%   |

Here it seems in a deck full of 2 mana spells, 18-20 lands is optimal. Greedy players could get away with 19.

