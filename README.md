# Advent of Code 2018
http://adventofcode.com/2018

Solutions with PHP 7.2

## Features
  * Download Input of the day
    * Doesn't override already existing files!
  * Test mode
    * Tweak the Puzzle code while making sure the solution doesn't get lost

## Install
`composer install`

[composer???](https://getcomposer.org/doc/00-intro.md)

In case you do not have curl installed:

`sudo apt-get install php-curl`

In case you do not have curl installed:

`sudo apt-get install php-xml`

Copy the .env.dist file

`cp .env.dist .env`

Insert your SESSION cookie for automated download of your personal puzzle input. 

Copy the testvalues.php.dist file

`cp Days/testvalues.php.dist Days/testvalues.php`

testvalues.php ist used for refactoring. Known puzzle solutions are tested against to tell if a change broke the code.

## Run
`./runDay.php 1`

Run a day.

`./runDay.php 1 2 3`

Runs multiple days.

`./runDay.php 2p2`

Runs only the second part of day 2.

`./runDay.php 2 --test`

Tests, if the results for day 2 changed.

`./runDay.php 2 --psr2-check`

Check code for PSR-2 violations

## Resources

  * [PHP Changes](http://php.net/manual/en/appendices.php)
    * [New PHP 7.0 Features](http://php.net/manual/en/migration70.new-features.php)
    * [New PHP 7.1 Features](http://php.net/manual/en/migration71.new-features.php)
    * [New PHP 7.2 Features](http://php.net/manual/en/migration72.new-features.php)
  * You might like "Modern PHP" by Josh Lockhart 
  * Day 11: [Summed-area table](https://en.wikipedia.org/wiki/Summed-area_table)
    * The first brute force approach took 40 minutes
    * Building and using an lookup table reduced the time down to 2.37 seconds
