<?php
return array(
	// set your paypal credential

	'ClientId' => 'AfzDPfxIspiEvH1-o8TsvEYFmHzRkLqZinbPu8M8xCec59SJbfbyw85dOCa7qFTAZGYfwTWN4vDES4wd',
 'ClientSecret' => 'EPIVRO2rVuSv9khNj4ba9VR22jwrO1YsNsVdKFcmk9u_iQpfWtjbJ6YdHgYdCIml-HJXjcmoVNFgE6_t',

//	'ClientId' => 'AfzDPfxIspiEvH1-o8TsvEYFmHzRkLqZinbPu8M8xCec59SJbfbyw85dOCa7qFTAZGYfwTWN4vDES4wd',
//	'ClientSecret' => 'EPIVRO2rVuSv9khNj4ba9VR22jwrO1YsNsVdKFcmk9u_iQpfWtjbJ6YdHgYdCIml-HJXjcmoVNFgE6_t',

/*//Amina sandbox
	'ClientId' => 'Ac0Zq0YbUHTi6ZgiOJHjMuttbeNVPtbvj0QRH7AD26IRbE9yz6E9_ZKfYbVeja_mxunHpKA_HeMX8gvV',
	'ClientSecret' => 'EHKKNVzZsXMmuL669QSUKqMMlrR5AYuA3HoL9AzXd6jWo1OCKJh3jt2QKBip01DKP9SDoqcKMv7FsGIt',*/

	'ClientId' => env('PAYPAL_CLIENT_ID'),
	'ClientSecret' => env('PAYPAL_CLIENT_SECRET'),

	/**
	 * SDK configuration
	 */
	'settings' => array(
		/**
		 * Available option 'sandbox' or 'live'
		 */
		'mode' => 'live',

		/**
		 * Specify the max request time in seconds
		 */
		'http.ConnectionTimeOut' => 30,

		/**
		 * Whether want to log to a file
		 */
		'log.LogEnabled' => true,

		/**
		 * Specify the file that want to write on
		 */
		'log.FileName' => storage_path() . '/logs/paypal.log',

		/**
		 * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
		 *
		 * Logging is most verbose in the 'FINE' level and decreases as you
		 * proceed towards ERROR
		 */
		'log.LogLevel' => 'FINE'
	),
);
