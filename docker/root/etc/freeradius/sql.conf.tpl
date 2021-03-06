sql {
	database = "mysql"
	driver = "rlm_sql_${database}"
	server = "$DB_HOST"
	login = "$DB_USERNAME"
	password = "$DB_PASSWORD"
	radius_db = "$DB_DATABASE"
	acct_table1 = "radacct"
	acct_table2 = "radacct"
	postauth_table = "radpostauth"
	authcheck_table = "radcheck"
	authreply_table = "radreply"
	groupcheck_table = "radgroupcheck"
	groupreply_table = "radgroupreply"
	usergroup_table = "radusergroup"
	deletestalesessions = yes
	sqltrace = no
	sqltracefile = ${logdir}/sqltrace.sql
	num_sql_socks = ${thread[pool].max_servers}
	connect_failure_retry_delay = 60
	lifetime = 0
	max_queries = 0
	readclients = yes
	nas_table = "nas"
	$INCLUDE sql/${database}/dialup.conf
}
