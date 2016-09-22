# Tick Talk
This repo contains the source code from the Nomad PHP Lightning Talk "Tick Talk" by Cal Evans. 

The program you are about to run, loads in the text of the poem "Xanadu". It then goes letter by letter guessing and re-guessing until it gets the letter correct. It then moves to the next letter until it has completely guessed all the letters in the poem. (We give it CRFLs for free). In this way, it is a very poor implementation of the idea [The Infinite Money Therom](https://en.wikipedia.org/wiki/Infinite_monkey_theorem). 

## But...WHY?
I needed something that:

- Would run for a long time
- I could loop.
- I could gather stats on

This idea fits the bill. It serves absolutely no other purpose other than to illustrate the new feature of PHP 7.1


## Files
- **Dockerfile**
The docker file you need to build PHP 7.1 If you already have PHP 7.1 built, you can safely ignore this
- **sigtest.php**
The main test file. Run this file using PHP 7.1 in one terminal session and open another terminal session connected the same machine or container to send signals from.
- **text.txt**
The source of the poem [Xanadu](https://www.poetryfoundation.org/poems-and-poets/poems/detail/43991) by Samuel Taylor Coleridge.
- **sendsig.sh**
A very small shell script that can be used to send a signal to sigtest.php. It must be run on the same machine or in the same docker instance as sigtest.php is running.
  - `USR1` Displays an abbreviated statistics display
  - `USR2` Resets all statistics
  - `TERM` Terminates the program at the end of the current run and displays the ending statistics.
  - `INT`  Terminates the program at the end of the current run and displays the ending statistics.


# Instructions

For this demo, you will need 2 terminal sessions open. If you are using the Docker container provided, you will need both sessions to be connected to the running container. If you have PHP 7.1 already installed on the machine you are running this on, there is no need to build or run the Docker container.

## Open the first terminal session
Again, if you are not using Docker, skip this.


We need to first build the image. Use this command.
```
$ docker build --tag php71_sandbox ./
```

## Once the build is complete, run it.
Once docker has built the image (15-45 minutes depending on your machine) you need to create a container from it using the `run` command.
```
$ docker run -ti -v ~/ticktalk/stuff:/opt php71_sandbox /bin/bash
```
You will need to change ~/ticktalk/stuff to the correct "stuff" directory from this repo. Docker won't allow you to use `./stuff/` so you will have to put in the full path and directory for it.


This should give you a command prompt as root in the container.


## Open a second terminal session
To be able to send signals we will need a second window in the container. You use the docker `exec` command to do this. 

```
$ docker ps
```
This will get you the container id for the next step.
$ docker exec <container id> -ti /bin/bash
```

Alternately, you can use this.
```
$ docker exec $(docker ps | grep php71_sandbox| awk {'print $1'}) /bin/bash
```

This should drop you to another command prompt in the container. 

it is best if you arrange your terminal windows so that you can see them both at the same time. They don't need to be big, you just want to be able to work in one and see the other.

## /opt
Now, in both windows navigate to the /opt directory and ensure that you have the files listed above.


## In the first terminal session
```
$ php sigtest.php
```
Run the `sigtest.php` program. If all goes well, you won't see anything at all happen.


## In the second terminal session
```
$ ./sendsig.sh USR1
```

Swapping to the second terminal session, use `sendsig.sh` to send one of the four signals to sigtest.php. The command above should cause sigtest.php to output the average number of guesses per run and the total number of guesses it has made. See above for the other signals you can send.


## In the first terminal session
If you wish, you can also press CTLR-C in the first window to stop the program. Since we catch `SIGINT`, this will cause the current run to complete, and then an orderly exit.


# Fin

If you are totally confused as to the point of this, watch the video.
