node 'log' {
	exec { 'apt-update':                    # exec resource named 'apt-update'
		command => '/usr/bin/apt-get update'  # command this resource will run
	}
	class { 'elasticsearch':
		manage_repo  => true,
		repo_version => '1.7',
		require  => Exec['apt-update'],
		version => '1.7.2',
		java_install => true
	}
	elasticsearch::instance { 'es-01':
		config => { },        # Configuration hash
		init_defaults => { }, # Init defaults hash
		datadir => '/data/es-data-es01' ,       # Data directory
	}
	elasticsearch::plugin{'lmenezes/elasticsearch-kopf':
		instances  => 'es-01'
	}
	class { 'kibana':
		version => "4.1.2"
	}
}