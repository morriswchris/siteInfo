siteInfo
========

Simple PHP script to curl site info. Currently using to curl lollapalooza for ticket info

Execute: php site_info.php url=[URL] times=[TIMES] sleep[SECONDS]

Params:
  url [ required ]: url of site to curl
	times [ optional ]: the amount of iterations to perform of the curl. default is one
	sleep [optional ]: amount of seconds before executing next iteration.
