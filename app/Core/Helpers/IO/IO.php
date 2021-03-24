<?php

namespace App\Core\Helpers\IO;

use Illuminate\Support\Facades\Facade;

/**
 * @see Proxy
 *
 * @method static void error(string $line) Write a line of output indicating an error
 * @method static void errors(array $lines) Write lines of output indicating an error
 * @method static void info(string $line) Write a line of output for information
 * @method static void infos(array $lines) Write lines of output for information
 * @method static void warning(string $line) Write a line of output to warn the user
 * @method static void warnings(array $lines) Write lines of output to warn the uer
 * @method static void success(string $line) Write a line of output to show a successful action
 * @method static void successes(array $lines) Write lines of output to show a successful action
 * @method static void writeln(string $line) Write a line of output
 * @method static void task(string $name, \Closure $task, $loadingText = 'running...') Run the given task
 * @method static void ask(string $question, $default = null, \Closure $validator = null) Asks the user a question until the validator returns true given the answer
 * @method static void progressStart(int $count) Starts a progress bar with the given number of steps
 * @method static void progressStep(int $count) Progresses the progress bar the given number of steps
 * @method static void progressFinish(int $count) Finishes the progress bar
 */
class IO extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Proxy::class;
    }

}
