<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
	'module_layouts' => array(
		'Frontend' => 'layout/frontend',
		'Backend' => 'layout/backend',
	),
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=cms_deposit;host=localhost;charset=utf8',
        'driver_option' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND  => "SET NAMES UTF8",
        ),
        'username' => 'root',
        'password' => '',
    ),
    'service_manager' => array(
        'factories' => array(
//            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',          
            'Zend\Db\Adapter\Adapter' => function ($serviceManager) {
				$adapterFactory = new Zend\Db\Adapter\AdapterServiceFactory();
				$adapter = $adapterFactory->createService($serviceManager);
				\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);
				return $adapter;
         }
        ),
    ),
    'static_salt' => 'qưertyuioZXCVMNBVGpasdfghjklzxcvbnm123456789ASDFGLKJHQWERPOIUT',
		'mail' => array(
			'transport' => array(
				'options' => array(
					'name'              => 'localhost',
					'host'              => 'mail.unimedia.vn',
					'connection_class'  => 'plain',
					'connection_config' => array(
						'username'				=> 'demo@unimedia.vn',
						'password'				=> '123654789',
						'port'						=> 25,
						'ssl'							=> 'tls'
					),
				),  
			),
		)
);
