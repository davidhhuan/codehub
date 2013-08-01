#!/usr/bin/expect -f
set project [lindex $argv 0]
switch $project {
    event {
    	set path test.com
	set password test
    }
    default {
        exit
    }
}
spawn ssh $path
expect "password:"
send $password
send "\r"
interact
