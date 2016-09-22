#!/bin/bash
kill -s $1 $(ps auxwww | grep sigtest | grep -v grep | awk '{print $1}')
