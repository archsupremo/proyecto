;
; BIND data file for local loopback interface
;
$TTL	604800
buyandsell.com.	IN	SOA	servidor. servidor.buyandsell.com. (
			      2		; Serial
			 604800		; Refresh
			  86400		; Retry
			2419200		; Expire
			 604800 )	; Negative Cache TTL
;
buyandsell.com.		IN	NS	servidor.buyandsell.com.
servidor.buyandsell.com.  IN	A	192.168.88.239
buyandsell.com.		IN	A	192.168.88.239
www			IN	CNAME	buyandsell.com.
