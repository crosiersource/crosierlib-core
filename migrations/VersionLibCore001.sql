SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `cfg_estabelecimento`;
CREATE TABLE `cfg_estabelecimento`
(
	`id`                 bigint(20) AUTO_INCREMENT NOT NULL,
	`codigo`             bigint(20)                NOT NULL,
	`descricao`          varchar(200)              NOT NULL,
	`concreto`           tinyint(1)                NOT NULL,
	`pai_id`             bigint(20), -- não é usado,
	`json_data`          json default null,
	`updated`            datetime,
	`inserted`           datetime,
	`estabelecimento_id` bigint(20)                NOT NULL,
	`user_inserted_id`   bigint(20),
	`user_updated_id`    bigint(20),
	PRIMARY KEY (`id`),
	UNIQUE KEY `UK_cfg_estabelecimento_codigo` (`codigo`),
	UNIQUE KEY `UK_cfg_estabelecimento_descricao` (`descricao`),
	KEY `K_cfg_estabelecimento_estabelecimento` (`estabelecimento_id`),
	KEY `K_cfg_estabelecimento_user_inserted` (`user_inserted_id`),
	KEY `K_cfg_estabelecimento_user_updated` (`user_updated_id`),
	CONSTRAINT `FK_cfg_estabelecimento_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
	CONSTRAINT `FK_cfg_estabelecimento_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_cfg_estabelecimento_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `sec_group`;
CREATE TABLE `sec_group`
(
	`id`                 bigint(20) AUTO_INCREMENT NOT NULL,
	`groupname`          varchar(90)               NOT NULL,
	`estabelecimento_id` bigint(20)                NOT NULL,
	`inserted`           datetime                  NOT NULL,
	`updated`            datetime                  NOT NULL,
	`user_inserted_id`   bigint(20)                NOT NULL,
	`user_updated_id`    bigint(20)                NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `UK_sec_group_groupname` (`groupname`),
	KEY `K_sec_group_estabelecimento` (`estabelecimento_id`),
	KEY `K_sec_group_user_inserted` (`user_inserted_id`),
	KEY `K_sec_group_user_updated` (`user_updated_id`),
	CONSTRAINT `FK_sec_group_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
	CONSTRAINT `FK_sec_group_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_sec_group_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `sec_role`;
CREATE TABLE `sec_role`
(
	`id`                 bigint(20) AUTO_INCREMENT NOT NULL,
	`role`               varchar(90)               NOT NULL,
	`descricao`          varchar(3000)             NOT NULL,
	`estabelecimento_id` bigint(20)                NOT NULL,
	`inserted`           datetime                  NOT NULL,
	`updated`            datetime                  NOT NULL,
	`user_inserted_id`   bigint(20)                NOT NULL,
	`user_updated_id`    bigint(20)                NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `UK_sec_role_role` (`role`),
	KEY `K_sec_role_estabelecimento` (`estabelecimento_id`),
	KEY `K_sec_role_user_inserted` (`user_inserted_id`),
	KEY `K_sec_role_user_updated` (`user_updated_id`),
	CONSTRAINT `FK_sec_role_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_sec_role_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_sec_role_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `sec_group_role`;
CREATE TABLE `sec_group_role`
(
	`group_id` bigint(20) NOT NULL,
	`role_id`  bigint(20) NOT NULL,
	KEY `K_sec_group_role_role` (`role_id`),
	KEY `K_sec_group_role_group` (`group_id`),
	CONSTRAINT `FK_sec_group_role_role` FOREIGN KEY (`role_id`) REFERENCES `sec_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_sec_group_role_group` FOREIGN KEY (`group_id`) REFERENCES `sec_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `sec_user`;
CREATE TABLE `sec_user`
(
	`id`                        bigint(20) AUTO_INCREMENT NOT NULL,
	`username`                  varchar(90)               NOT NULL,
	`nome`                      varchar(90)               NOT NULL,
	`descricao`                 VARCHAR(255)              NULL,
	`email`                     varchar(90)               NOT NULL,
	`fone`                      varchar(50) default null,
	`token_recupsenha`          char(36)    default null,
	`dt_valid_token_recupsenha` datetime    default null,
	`password`                  varchar(255),
	`ativo`                     tinyint(1)                NOT NULL,
	`group_id`                  bigint(20),
	`api_token`                 varchar(255),
	`api_token_expires_at`      datetime,
	`session_id`                varchar(200),
	`estabelecimento_id`        bigint(20)                NOT NULL,
	`inserted`                  datetime                  NOT NULL,
	`updated`                   datetime                  NOT NULL,
	`user_inserted_id`          bigint(20)                NOT NULL,
	`user_updated_id`           bigint(20)                NOT NULL,
	`version`                   int(11),
	PRIMARY KEY (`id`),
	UNIQUE KEY `UK_sec_user_username_estabelecimento` (`username`, `estabelecimento_id`) USING BTREE,

	KEY `K_sec_user_estabelecimento` (`estabelecimento_id`),
	KEY `K_sec_user_user_inserted` (`user_inserted_id`),
	KEY `K_sec_user_user_updated` (`user_updated_id`),
	KEY `K_sec_user_group` (`group_id`),
	CONSTRAINT `FK_sec_user_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
	CONSTRAINT `FK_sec_user_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_sec_user_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_sec_user_group` FOREIGN KEY (`group_id`) REFERENCES `sec_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `sec_user_role`;
CREATE TABLE `sec_user_role`
(
	`user_id` bigint(20) NOT NULL,
	`role_id` bigint(20) NOT NULL,
	`updated` date,
	KEY `K_sec_user_role_role` (`role_id`),
	KEY `K_sec_user_role_user` (`user_id`),
	CONSTRAINT `FK_sec_user_role_role` FOREIGN KEY (`user_id`) REFERENCES `sec_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_sec_user_role_user` FOREIGN KEY (`role_id`) REFERENCES `sec_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `cfg_app`;
CREATE TABLE `cfg_app`
(
	`id`                 bigint(20) AUTO_INCREMENT NOT NULL,
	`uuid`               char(36)                  NOT NULL,
	`nome`               varchar(300)              NOT NULL,
	`obs`                varchar(5000),
	`inserted`           datetime                  NOT NULL,
	`updated`            datetime                  NOT NULL,
	`estabelecimento_id` bigint(20)                NOT NULL,
	`user_inserted_id`   bigint(20)                NOT NULL,
	`user_updated_id`    bigint(20)                NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `cfg_app_nome` (`nome`),
	UNIQUE KEY `cfg_app_uuid` (`uuid`),
	KEY `K_cfg_app_estabelecimento` (`estabelecimento_id`),
	KEY `K_cfg_app_user_inserted` (`user_inserted_id`),
	KEY `K_cfg_app_user_updated` (`user_updated_id`),
	CONSTRAINT `FK_cfg_app_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
	CONSTRAINT `FK_cfg_app_user_inserted` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_cfg_app_user_updated` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `cfg_app_config`;
CREATE TABLE `cfg_app_config`
(
	`id`                 bigint(20) AUTO_INCREMENT NOT NULL,
	`chave`              varchar(255)              NOT NULL,
	`valor`              LONGTEXT,
	`is_json`            tinyint(1),
	`app_uuid`           char(36)                  NOT NULL,
	`inserted`           datetime                  NOT NULL,
	`updated`            datetime                  NOT NULL,
	`estabelecimento_id` bigint(20)                NOT NULL,
	`user_inserted_id`   bigint(20)                NOT NULL,
	`user_updated_id`    bigint(20)                NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `UK_cfg_app_config_chave_app` (`chave`, `app_uuid`),
	KEY `K_cfg_app_config_app` (`app_uuid`),
	CONSTRAINT `FK_cfg_app_config_app` FOREIGN KEY (`app_uuid`) REFERENCES `cfg_app` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
	KEY `K_cfg_app_config_estabelecimento` (`estabelecimento_id`),
	KEY `K_cfg_app_config_user_inserted` (`user_inserted_id`),
	KEY `K_cfg_app_config_user_updated` (`user_updated_id`),
	CONSTRAINT `FK_cfg_app_config_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
	CONSTRAINT `FK_cfg_app_config_user_inserted` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_cfg_app_config_user_updated` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `cfg_menu_item`;
CREATE TABLE `cfg_menu_item`
(
	`id`                 bigint(20) AUTO_INCREMENT NOT NULL,
	`crosier_app`        varchar(255)              NOT NULL,
	`label`              varchar(255)              NOT NULL,
	`icon`               varchar(50),
	`tipo`               varchar(50)               NOT NULL,
	`pai_id`             bigint(20),
	`ordem`              int(11)                   NOT NULL,
	`css_style`          varchar(2000),
	`url`                varchar(500),
	`roles`              varchar(2000),
	`inserted`           datetime                  NOT NULL,
	`updated`            datetime                  NOT NULL,
	`estabelecimento_id` bigint(20)                NOT NULL,
	`user_inserted_id`   bigint(20)                NOT NULL,
	`user_updated_id`    bigint(20)                NOT NULL,
	UNIQUE KEY `UK_cfg_menu_item_label_url` (`label`, `url`),
	KEY `K_cfg_menu_item_pai` (`pai_id`),
	CONSTRAINT `FK_cfg_menu_item_pai` FOREIGN KEY (`pai_id`) REFERENCES `cfg_menu_item` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY (`id`),
	KEY `K_cfg_menu_item_estabelecimento` (`estabelecimento_id`),
	KEY `K_cfg_menu_item_user_inserted` (`user_inserted_id`),
	KEY `K_cfg_menu_item_user_updated` (`user_updated_id`),
	CONSTRAINT `FK_cfg_menu_item_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
	CONSTRAINT `FK_cfg_menu_item_user_inserted` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
	CONSTRAINT `FK_cfg_menu_item_user_updated` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions`
(
	`version`        varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
	`executed_at`    datetime DEFAULT NULL,
	`execution_time` int      DEFAULT NULL,
	PRIMARY KEY (`version`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

DROP TABLE IF EXISTS `refresh_tokens`;
CREATE TABLE refresh_tokens
(
	id            INT AUTO_INCREMENT NOT NULL,
	refresh_token VARCHAR(128)       NOT NULL,
	username      VARCHAR(255)       NOT NULL,
	valid         DATETIME           NOT NULL,
	UNIQUE INDEX UK_refresh_tokens (refresh_token),
	PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE cfg_estabelecimento;
TRUNCATE TABLE sec_user;
TRUNCATE TABLE sec_group;
TRUNCATE TABLE sec_role;
TRUNCATE TABLE sec_group_role;
TRUNCATE TABLE sec_user_role;

INSERT INTO cfg_estabelecimento(id, codigo, descricao, concreto, pai_id, updated, inserted, user_inserted_id,
								user_updated_id, estabelecimento_id)
VALUES (1, 1, 'ADMIN', true, null, now(), now(), 1, 1, 1);

-- Senha padrão: admin@123
INSERT INTO sec_user(id, username, nome, email, password, ativo, group_id, estabelecimento_id, updated, inserted,
					 user_inserted_id, user_updated_id)
VALUES (1, 'admin', 'Admin', 'admin@email.com',
		'$argon2id$v=19$m=65536,t=4,p=1$3mj2TxDtNWJsp0EkjC0bDQ$0L8SC83i3cmjGfYxet7DkmzA+/wsWUp09Yg9l7qNcBk', true, 1, 1,
		now(), now(), 1, 1);
INSERT INTO sec_user(id, username, nome, email, password, ativo, group_id, estabelecimento_id, updated, inserted,
					 user_inserted_id, user_updated_id, api_token, api_token_expires_at)
VALUES (2, 'uploader', 'UPLOADER', 'upload@crosier.com.br', '', false, 1, 1, now(), now(), 1, 1, '999999',
		'2900-12-31');

-- admin@123
INSERT INTO sec_group(id, groupname, estabelecimento_id, updated, inserted, user_inserted_id, user_updated_id)
VALUES (1, 'ADMIN', 1, now(), now(), 1, 1);

INSERT INTO sec_role(id, role, descricao, estabelecimento_id, updated, inserted, user_inserted_id, user_updated_id)
VALUES (null, 'ROLE_ADMIN', 'Usuário "root" do sistema', 1, now(), now(), 1, 1),
	   (null, 'ROLE_ALLOWED_TO_SWITCH', 'Permite que o usuário alterne para qualquer outro usuário do sistema', 1,
		now(), now(), 1, 1),
	   (null, 'ROLE_UPLOAD', 'Permissão para enviar arquivos através da API de upload', 1, now(), now(), 1, 1),
	   (null, 'ROLE_ENTITY_CHANGES', 'Pode visualizar os registros de alterações das entidades.', 1, now(), now(), 1,
		1),
	   (null, 'ROLE_NENHUMA', 'Role sem efeito (serve apenas para poder deixar um usuário com apenas 1 role).', 1,
		now(), now(), 1, 1),
	   (null, 'ROLE_ALLOWED_TO_SWITCH_IF_SAME_EMAIL',
		'Permite que o usuário alterne entre outros usuários que possuam o mesmo e-mail.', 1, now(), now(), 1, 1);
INSERT INTO sec_group_role(group_id, role_id)
VALUES (1, 1);

INSERT INTO sec_user_role(user_id, role_id)
VALUES (1, 1);
INSERT INTO sec_user_role(user_id, role_id)
VALUES (2, 3);

DELETE
FROM cfg_app
WHERE uuid = '175bd6d3-6c29-438a-9520-47fcee653cc5';
INSERT INTO `cfg_app` (`id`, `uuid`, `inserted`, `updated`, `nome`, `obs`, `estabelecimento_id`, `user_inserted_id`,
					   `user_updated_id`)
VALUES (1, '175bd6d3-6c29-438a-9520-47fcee653cc5', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 'crosierlib-core',
		'Núcleo do Crosier', 1, 1, 1);

DELETE
FROM cfg_app_config
WHERE chave = 'URL_devlocal'
  AND app_uuid = '175bd6d3-6c29-438a-9520-47fcee653cc5';
