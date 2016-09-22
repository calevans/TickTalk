<?php
/*
 * Setup
 */
$monolog        = file_get_contents('./text.txt');
$finished       = '';
$maxChars       = strlen($monolog);
$counter        = 0;
$conversions    = 0;
$runs           = [];
$totalGuesses   = 0;
$grandTotal     = 0;
$continue       = true;

/* 
 * Set signal handler
 */
pcntl_async_signals(on);
pcntl_signal(SIGUSR1, "displayStatus");
pcntl_signal(SIGUSR2, "resetCounter");
pcntl_signal(SIGTERM, "caughtSigTerm");
pcntl_signal(SIGINT, "caughtSigTerm");

/*
 * One big loop
 */
while ($continue) {
        $totalGuesses = 0;
        $finished     = '';
        $counter      = 0;
        while ($counter<$maxChars) {

                if (ord($monolog[$counter])===13 or ord($monolog[$counter])===10) {
                        $finished .= PHP_EOL;
                        $counter++;
                        continue;
                }

                $guess = null;

                while ($guess!==$monolog[$counter]) {
                        $guess = chr(random_int(32,125));
                        $totalGuesses++;
                }
                $finished .=$guess;
                $counter++ ;
        }
        $runs[] = $totalGuesses;

}

/*
 * Ok, we are done now.
 */
displayStatus(true);
echo "\nDone\n";
die(0);

/* 
 * What to do when sent a USR1
 */
function displayStatus($final=false)
{
        global $runs, $finished;
        $grandTotal = 0;

        echo "\nTotal Runs:" . count($runs) . "\n";
        if ($final===true) {
                echo "\nTotal Guesses:\n";
        }

        foreach($runs as $key=>$thisRun) {
                if ($final===true) {
                        echo "  Run " . $key . ": " . number_format($thisRun,0) . "\n";
                }
                $grandTotal +=$thisRun;
        }

        echo "Grand Total             : " . number_format($grandTotal,0) . "\n";
        echo "Average Guesses Per Run : " . number_format($grandTotal/count($runs),0);
        echo "\n";
        return;
}
// exit

function resetCounter()
{
    global $runs;
    $runs = [];
    return;
}

/* 
 * What to do when sent a TERM
 */
function caughtSigTerm()
{
        global $continue;
        $continue = false;
        return;
}        