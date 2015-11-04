#!/usr/bin/perl
use Sys::Syslog qw( :DEFAULT setlogsock );
 
setlogsock('unix');
openlog('wsp_access', 'cons', 'local1');
 
while ($log = <STDIN>) {
    syslog('info', $log);
}
closelog