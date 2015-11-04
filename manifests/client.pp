node 'client' {
 exec { 'apt-update':                    # exec resource named 'apt-update'
  command => '/usr/bin/apt-get update'  # command this resource will run
 }
 file { [ "/var/www/", "/var/www/WebSessionProfiler/" ]:
    ensure => "directory",
 }
 	package { "syslog-ng":
		require  => Exec['apt-update'],
		ensure => "installed"
	}
    service { "syslog-ng":
		ensure  => "running",
		enable  => "true",
		require => Package["syslog-ng"],
    }

 class { 'apache':                # use the "apache" module
    require  => Exec['apt-update'],
    default_vhost => false,        # don't use the default vhost
    default_mods => false,         # don't load default mods
    mpm_module => 'prefork',        # use the "prefork" mpm_module
  }
   include apache::mod::php        # include mod php
   apache::vhost { '127.0.0.1':  # create a vhost called "example.com"
    port    => '80',               # use port 80
    docroot => '/var/www/WebSessionProfiler/htdocs',     # set the docroot to the /var/www/html
	access_log_pipe => '|/usr/local/bin/logger.pl',
	access_log_format => '{\"wsp_application\":\"%{X-WebSessionProfilerName}o\", \"client\": \"%a\", \"host\": \"%h\", \"forwardedip\": \"%{X-Forwarded-For}i\", \"duration_usec\": %D, \"duration_sec\": %T, \"ident\": \"%l\", \"auth\": \"%u\", \"@timestamp\": \"%{%Y-%m-%dT%H:%M:%S%z}t\", \"forwarded\": \"%X\", \"request\": \"%U\", \"query_string\": \"%q\", \"method\": \"%m\", \"status\": %s, \"ResponseSize\": \"%b\", \"referrer\": \"%{Referer}i\", \"useragent\": \"%{User-Agent}i\", \"vhost\": \"%V\", \"wspid\":\"%{X-WebSessionProfilerId}o\", \"wspsessid\":\"%{X-WebSessionProfilerSessionId}o\", \"wspsessinc\":\"%{X-WebSessionProfilerSessionInc}o\"}',
	override => 'all'
  }
  apache::mod
{"env": }

   file { '/var/www/WebSessionProfiler/htdocs':
   ensure => 'link',
   target => '/vagrant_data/htdocs',

 }
	file { '/etc/syslog-ng/conf.d/wsp_applog.conf':
   ensure => 'link',
   target => '/vagrant_data/etc/syslog-ng/conf.d/wsp_applog.conf',
   notify  => Service["syslog-ng"],
   require => Package["syslog-ng"]
 }
 file { '/etc/syslog-ng/conf.d/wsp_kv.conf':
   ensure => 'link',
   target => '/vagrant_data/etc/syslog-ng/conf.d/wsp_kv.conf',
   notify  => Service["syslog-ng"],
   require => Package["syslog-ng"]
 }
  file { '/etc/syslog-ng/conf.d/wsp_otherinterface.conf':
   ensure => 'link',
   target => '/vagrant_data/etc/syslog-ng/conf.d/wsp_otherinterface.conf',
      notify  => Service["syslog-ng"],
	  require => Package["syslog-ng"]
 }
  file { '/etc/syslog-ng/conf.d/wsp_sql.conf':
   ensure => 'link',
   target => '/vagrant_data/etc/syslog-ng/conf.d/wsp_sql.conf',
      notify  => Service["syslog-ng"],
	  require => Package["syslog-ng"]
 }
   file { '/etc/syslog-ng/conf.d/wsp_accesslog.conf':
   ensure => 'link',
   target => '/vagrant_data/etc/syslog-ng/conf.d/wsp_accesslog.conf',
      notify  => Service["syslog-ng"],
	  require => Package["syslog-ng"]
 }
  file { '/usr/local/bin/logger.pl':
   ensure => 'link',
   target => '/vagrant_data/usr/local/bin/logger.pl',
   mode => 755
 }
}