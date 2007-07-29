CREATE DATABASE debian_l10n;

GRANT ALL
  ON debian_l10n.*
  TO USER@localhost IDENTIFIED BY 'PASSWD';

USE debian_l10n;

CREATE TABLE `translation` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `name` text NOT NULL,
  `team` text NOT NULL,
  `status` set('ITT','RFR','RFR2','LCFC','BTS','DONE') NOT NULL default '',
  `type` enum('po-debconf','debian-installer','po','man','wml') NOT NULL default 'po-debconf',
  `translator` text NOT NULL,
  `file` text,
  `bugnr` bigint(20) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM

