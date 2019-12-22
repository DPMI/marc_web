<?php
foreach ( MP::selection() as $mp ){
	$table = "{$mp->MAMPid}_filterlist";
	migration_sql("INSERT INTO `filter` SELECT `filter_id`, {$mp->id} AS `mp_id`, `mode`, `ind` AS `index`, `CI_ID` AS `CI`, `VLAN_TCI`, `VLAN_TCI_MASK`, `ETH_TYPE`, `ETH_TYPE_MASK`, `ETH_SRC`, `ETH_SRC_MASK`, `ETH_DST`, `ETH_DST_MASK`, `IP_PROTO`, `IP_SRC`, `IP_SRC_MASK`, `IP_DST`, `IP_DST_MASK`, `SRC_PORT`, `SRC_PORT_MASK`, `DST_PORT`, `DST_PORT_MASK`, `destaddr`, `type`, `caplen` FROM `$table`");
	migration_sql("DROP TABLE `$table`");
}
