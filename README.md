# Poker hand evaluator ♠️♣️♥️♦️

## How to use

1. Run the Docker containers with `make start`  
2. Install the dependencies and configure the database with `make install` 
2. Go to the URL http://localhost:8000/  
3. Login with the credentials below
4. Upload the file containing player hands 

**login:** _john@doe.com_   
**password:** _pass_

## Run tests

    make test

The file containing all the hand possibilities is big, therefore the test `testEvaluateAllHands` is long.

## Credits  
The evaluator implements Kevin Suffecool's 5-card hand evaluator, with the perfect hash optimization by Paul Senzee.
