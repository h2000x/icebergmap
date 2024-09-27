CREATE TABLE tx_icebergmap_domain_model_iceberg (
	name varchar(255) NOT NULL DEFAULT '',
    firstappearance int(11) NOT NULL DEFAULT '0',
);

CREATE TABLE tx_icebergmap_domain_model_icebergdata (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    sys_language_uid int(11) DEFAULT '0' NOT NULL,
     latitude                 double(11, 6) DEFAULT '0.000000' NOT NULL,
     longitude                double(11, 6) DEFAULT '0.000000' NOT NULL,
     length                 double(11, 6) DEFAULT '0.00' NOT NULL,
     width                double(11, 6) DEFAULT '0.00' NOT NULL,
     squarekm                 double(11, 6) DEFAULT '0.00' NOT NULL,
     datadate int(11) DEFAULT '0' NOT NULL,
     iceberg int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
);
