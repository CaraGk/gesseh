CREATE TABLE gesseh_critere (id BIGINT AUTO_INCREMENT, form BIGINT NOT NULL, titre VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, ratio BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX form_idx (form), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_etudiant (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, promo_id BIGINT NOT NULL, email VARCHAR(255) NOT NULL, token_mail VARCHAR(255) NOT NULL, tel VARCHAR(14), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX promo_id_idx (promo_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_eval (id BIGINT AUTO_INCREMENT, stage_id BIGINT NOT NULL, critere_id BIGINT NOT NULL, valeur VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX stage_id_idx (stage_id), INDEX critere_id_idx (critere_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_hopital (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL UNIQUE, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255), web VARCHAR(255), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_param (id BIGINT AUTO_INCREMENT, titre VARCHAR(255) NOT NULL, valeur VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_periode (id BIGINT AUTO_INCREMENT, debut DATE NOT NULL, fin DATE NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_promo (id BIGINT AUTO_INCREMENT, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_stage (id BIGINT AUTO_INCREMENT, terrain_id BIGINT NOT NULL, periode_id BIGINT NOT NULL, etudiant_id BIGINT NOT NULL, is_active TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX terrain_id_idx (terrain_id), INDEX periode_id_idx (periode_id), INDEX etudiant_id_idx (etudiant_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_terrain (id BIGINT AUTO_INCREMENT, hopital_id BIGINT NOT NULL, filiere VARCHAR(255) NOT NULL, patron VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, form_id BIGINT DEFAULT 1 NOT NULL, is_active TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX hopital_id_idx (hopital_id), INDEX form_id_idx (form_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE cs_setting (id INT AUTO_INCREMENT, name VARCHAR(255) NOT NULL UNIQUE, type VARCHAR(255) DEFAULT 'input' NOT NULL, widget_options LONGTEXT, value LONGTEXT, setting_group VARCHAR(255) DEFAULT NULL, setting_default LONGTEXT DEFAULT NULL, slug VARCHAR(255), UNIQUE INDEX cs_setting_sluggable_idx (slug), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group (id INT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group_permission (group_id INT, permission_id INT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_permission (id INT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_remember_key (id INT AUTO_INCREMENT, user_id INT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id, ip_address)) ENGINE = INNODB;
CREATE TABLE sf_guard_user (id INT AUTO_INCREMENT, username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_group (user_id INT, group_id INT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_permission (user_id INT, permission_id INT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
ALTER TABLE gesseh_etudiant ADD CONSTRAINT gesseh_etudiant_promo_id_gesseh_promo_id FOREIGN KEY (promo_id) REFERENCES gesseh_promo(id) ON DELETE CASCADE;
ALTER TABLE gesseh_eval ADD CONSTRAINT gesseh_eval_stage_id_gesseh_stage_id FOREIGN KEY (stage_id) REFERENCES gesseh_stage(id) ON DELETE CASCADE;
ALTER TABLE gesseh_eval ADD CONSTRAINT gesseh_eval_critere_id_gesseh_critere_id FOREIGN KEY (critere_id) REFERENCES gesseh_critere(id) ON DELETE CASCADE;
ALTER TABLE gesseh_stage ADD CONSTRAINT gesseh_stage_terrain_id_gesseh_terrain_id FOREIGN KEY (terrain_id) REFERENCES gesseh_terrain(id) ON DELETE CASCADE;
ALTER TABLE gesseh_stage ADD CONSTRAINT gesseh_stage_periode_id_gesseh_periode_id FOREIGN KEY (periode_id) REFERENCES gesseh_periode(id) ON DELETE CASCADE;
ALTER TABLE gesseh_stage ADD CONSTRAINT gesseh_stage_etudiant_id_gesseh_etudiant_id FOREIGN KEY (etudiant_id) REFERENCES gesseh_etudiant(id) ON DELETE CASCADE;
ALTER TABLE gesseh_terrain ADD CONSTRAINT gesseh_terrain_hopital_id_gesseh_hopital_id FOREIGN KEY (hopital_id) REFERENCES gesseh_hopital(id) ON DELETE CASCADE;
ALTER TABLE gesseh_terrain ADD CONSTRAINT gesseh_terrain_form_id_gesseh_critere_form FOREIGN KEY (form_id) REFERENCES gesseh_critere(form);
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_remember_key ADD CONSTRAINT sf_guard_remember_key_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
