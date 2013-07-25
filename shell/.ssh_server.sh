#!/usr/bin/expect -f
set project [lindex $argv 0]
switch $project {
    dev {
        set path shopnc@172.26.0.27
        set password abc123!
    }
    qa {
        set path shopnc@172.26.0.51
        set password abc@2!1n
    }
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
