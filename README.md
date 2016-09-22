# Tick Talk
This repo contains the source code form the Nomad PHP Lightning Talk "Tick Talk" by Cal Evans. 

## Files
- Dockerfile
The docker file you need to build PHP 7.1 If you alreayd have PHP 7.1 built, you can safely ignore this
- sigtest.php
The main test file. run this file using PHP 7.1 in one window and open another window in the same machine to send signals from.
- text.txt
The source of the poem [https://www.poetryfoundation.org/poems-and-poets/poems/detail/43991](Xanadu) by Samuel Taylor Coleridge.
-sendsig.sh
A small shell script that can be used to send a signal to sigtest.php. It must be run on the same machine or in the same docker instance as sigtest.php is running.
  - `USR1` Displays an abbreviated statistics display
  - `USR2` Resets all statistics
  - `TERM` Terminates the program at the end of the current run and displays the ending statistics.
  - `INT`  Terminates the program at the end of the current run and displays the ending statistics.


# Instructions

For this demo, you will need 2 terminal windows open. If yoyu are using the Docker container provided, you will need both windows to be connected nto the running container. If you have PHP 7.1 already installed on the machine you are running this on, there is no need to run the Docker container.

## Open the first terminal session
Again, if you are not using Docker, skip this.


If you are using docker, we need to first build the image. Use this command.
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
``

Alternately, you can use this.
```
$ docker exec $(docker ps | grep php71_sandbox| awk {'print $1'}) /bin/bash
``
This should drop you to another command prompt in the container. 

it is best if you arrange your terminal windows so that you can see them both at the same time. They don't need to be big, you just want to be able to work in one and see the other.

## /opt
Now, in both windows navigate to the /opt directory and ensure that you have the files listed above.


## In the first terminal session
```
$ php sigtest.php
```

## In the second terminal session
```
$ ./sendsig.sh USR1
```

This should cause sigtest.php to output the average number of guesses per run and the total number of guesses it has made. See above for the other signals you can send.

## In the first terminal session
If you wish, you can also press CTLR-C in the first window to stop the program. Since we catch `SIGINT`, this will cause the current run to complete, and then an orderly exit.




