CREATE TABLE IF NOT EXISTS `mc_plugins_ogone` (
  `idogone` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pspid_og` varchar(80) NOT NULL,
  `passphrase_og` varchar(80) NOT NULL,
  `formaction_og` varchar(40) NOT NULL DEFAULT 'test',
  PRIMARY KEY (`idogone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;