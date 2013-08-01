#!/usr/bin/expect -f
set project [lindex $argv 0]
switch $project {
    event {
    	set path heng@event.shedunews.com
	set password heng123456
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
